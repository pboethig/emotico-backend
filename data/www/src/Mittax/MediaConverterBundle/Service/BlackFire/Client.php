<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 18.12.16
 * Time: 17:54
 */

namespace Mittax\MediaConverterBundle\Service\BlackFire;


use Blackfire\ClientConfiguration;
use Mittax\MediaConverterBundle\Service\System\Config;

final class Client
{
    /**
     * @var \Blackfire\Client[]
     */
    private static $_client;

    /**
     * @var
     */
    private static $_config;

    /**
     * @var string
     */
    private static $_clientId;

    /**
     * @var string
     */
    private static $_env;
    /**
     * @var string
     */
    private static $_clientToken;


    public function __construct()
    {
        throw new \BadMethodCallException('Class cannot be instanciated. Use getClient() instead');
    }

    /**
     * @param string|null $env
     * @return bool
     */
    public static function configure(string $env = null) : bool
    {
        $config = Config::getMediaConverterConfig();

        self::$_config = $config['profilers']['blackfire'];

        self::$_clientId = self::$_config['clientId'];

        self::$_clientToken = self::$_config['clientToken'];

        self::$_env = $env;

        return true;
    }

    /**
     * @param string $env
     * @return \Blackfire\Client
     */
    public static function getClient(string $env = null) : \Blackfire\Client
    {
        self::configure($env);

        $_config = new ClientConfiguration(self::$_clientId, self::$_clientToken, $env);

        return new \Blackfire\Client($_config);
    }

    /**
     * @return mixed
     */
    public static function getConfig() : ClientConfiguration
    {
        return self::$_config;
    }

    /**
     * @return string
     */
    public static function getClientId() : string
    {
        return self::$_clientId;
    }

    /**
     * @return string
     */
    public static function getClientToken() : string
    {
        return self::$_clientToken;
    }
}