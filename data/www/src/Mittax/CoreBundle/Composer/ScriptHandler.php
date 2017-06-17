<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mittax\CoreBundle\Composer;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\Event;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ScriptHandler
{
    /**
     * Composer variables are declared static so that an event could update
     * a composer.json and set new options, making them immediately available
     * to forthcoming listeners.
     */
    protected static $options = array(
        'symfony-app-dir' => 'app',
        'symfony-web-dir' => 'web',
        'symfony-assets-install' => 'hard',
        'symfony-cache-warmup' => false,
    );

    /**
     * Installs the assets under the web root directory.
     *
     * For better interoperability, assets are copied instead of symlinked by default.
     *
     * Even if symlinks work on Windows, this is only true on Windows Vista and later,
     * but then, only when running the console with admin rights or when disabling the
     * strict user permission checks (which can be done on Windows 7 but not on Windows
     * Vista).
     *
     * @param Event $event
     */
    public static function installDatabase(Event $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'install assets');

        if (null === $consoleDir) {
            return;
        }

        $event->getIO()->write("--------------------------------------------------------");
        $event->getIO()->write(" Install database");
        $event->getIO()->write("--------------------------------------------------------");


        static::executeCommand($event, $consoleDir, 'doctrine:database:create --env=prod --if-not-exists');

        static::executeCommand($event, $consoleDir, 'doctrine:schema:update --env=prod --force --quiet');

        /**
         * Dev database
         */
        static::executeCommand($event, $consoleDir, 'doctrine:database:create --env=dev --if-not-exists');

        $process = static::executeCommand($event, $consoleDir, 'doctrine:schema:update --env=dev --force --quiet');

        /**
         * Support fos_user on prod
         */
        self::_installDefaultUserOnFosUserBundle($event, $consoleDir, 'prod');

        /**
         * Support fos_user o dev
         */
        self::_installDefaultUserOnFosUserBundle($event, $consoleDir, 'dev');

        /**
         * set filesystem rights
         */
        self::_setAccessRights($process, $event);

        $event->getIO()->write("--------------------------------------------------------");
        $event->getIO()->write(" Installation complete. Websocketserver is started on 8080");
        $event->getIO()->write("--------------------------------------------------------");

        try
        {
            $process = static::executeCommand($event, $consoleDir, 'gos:websocket:server');
        }
        catch(\Exception $ex)
        {

        }
    }

    /**
     * @param Process $process
     * @param Event $event
     */
    private static function _setAccessRights(Process $process, Event $event)
    {
        /**
         * @todo: find the right accessrights, worldwide cant be.
         */
        $path = __DIR__ . '/../../../../';

        $process = new Process('chmod -R 777 ' . $path);

        $process->run(function ($type, $buffer) use ($event)
        {
            if (Process::ERR === $type)
            {
                $event->getIO()->write("Error setting accessrights: " . $buffer); ;
            }
            else
            {
                $event->getIO()->write("  - Success setting accessrights"); ;
            }
        });
    }

    /**
     * @param $event
     * @param $consoleDir
     */
    private static function _installDefaultUserOnFosUserBundle($event, $consoleDir, $environment)
    {
        $process = static::executeCommand($event, $consoleDir, 'config:dump-reference');

        if(strstr($process->getOutput(), 'FOSUserBundle'))
        {
            $event->getIO()->write("--------------------------------------------------------");
            $event->getIO()->write(" Install default data on: ". $environment);
            $event->getIO()->write("--------------------------------------------------------");

            $event->getIO()->write("- FOS User enabled. Importing default user");

            $sql = file_get_contents(__DIR__ . "/imports/fos_user/defaultuser.sql");

            $command = 'doctrine:query:sql "' . $sql . '" --env=' . $environment;

            static::executeCommand($event, $consoleDir, $command);
        }
    }

    /**
     * @param Event $event
     * @param $configName
     * @param $path
     * @param $actionName
     * @return bool
     */
    protected static function hasDirectory(Event $event, $configName, $path, $actionName)
    {
        if (!is_dir($path)) {
            $event->getIO()->write(sprintf('The %s (%s) specified in composer.json was not found in %s, can not %s.', $configName, $path, getcwd(), $actionName));

            return false;
        }

        return true;
    }

    /**
     * Clears the Symfony cache.
     *
     * @param Event $event
     */
    public static function clearCache(Event $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'clear the cache');

        if (null === $consoleDir) {
            return;
        }

        $warmup = '';
        if (!$options['symfony-cache-warmup']) {
            $warmup = ' --no-warmup';
        }

        static::executeCommand($event, $consoleDir, 'cache:clear'.$warmup, $options['process-timeout']);
    }


    /**
     * @param Event $event
     * @param $consoleDir
     * @param $cmd
     * @param int $timeout
     * @return Process
     */
    protected static function executeCommand(Event $event, $consoleDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(static::getPhp(false));
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $console = escapeshellarg($consoleDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.($phpArgs ? ' '.$phpArgs : '').' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($event) { $event->getIO()->write($buffer, false); });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s.", escapeshellarg($cmd), $process->getOutput(), $process->getErrorOutput()));
        }

        return $process;
    }

    /**
     * @param Event $event
     * @return array
     */
    protected static function getOptions(Event $event)
    {
        $options = array_merge(static::$options, $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];
        $options['symfony-cache-warmup'] = getenv('SYMFONY_CACHE_WARMUP') ?: $options['symfony-cache-warmup'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find($includeArgs)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    protected static function getPhpArguments()
    {
        $arguments = array();

        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }

        if (false !== $ini = php_ini_loaded_file()) {
            $arguments[] = '--php-ini='.$ini;
        }

        return $arguments;
    }

    /**
     * Returns a relative path to the directory that contains the `console` command.
     *
     * @param Event  $event      The command event
     * @param string $actionName The name of the action
     *
     * @return string|null The path to the console directory, null if not found.
     */
    protected static function getConsoleDir(Event $event, $actionName)
    {
        $options = static::getOptions($event);

        if (static::useNewDirectoryStructure($options)) {
            if (!static::hasDirectory($event, 'symfony-bin-dir', $options['symfony-bin-dir'], $actionName)) {
                return;
            }

            return $options['symfony-bin-dir'];
        }

        if (!static::hasDirectory($event, 'symfony-app-dir', $options['symfony-app-dir'], 'execute command')) {
            return;
        }

        return $options['symfony-app-dir'];
    }

    /**
     * Returns true if the new directory structure is used.
     *
     * @param array $options Composer options
     *
     * @return bool
     */
    protected static function useNewDirectoryStructure(array $options)
    {
        return isset($options['symfony-var-dir']) && is_dir($options['symfony-var-dir']);
    }
}
