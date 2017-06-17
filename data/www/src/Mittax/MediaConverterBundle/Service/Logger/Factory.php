<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 08.12.16
 * Time: 20:20
 */

namespace Mittax\MediaConverterBundle\Service\Logger;

use \Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Monolog\Handler\StreamHandler;
class Factory
{
    /**
     * @var ContainerInterface
     */
    private $_containerInterface;

    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * @var string
     */
    private $_fileName;
    /**
     * Factory constructor.
     * @param ContainerInterface $containerInterface
     */
    public function __construct(ContainerInterface $containerInterface)
    {
        $this->_containerInterface = $containerInterface;
    }

    /**
     * @return bool
     */
    public function buildLogger() : bool
    {
        $this->_logger = new Logger('mediaconverter');

        $this->_logger->pushHandler(new StreamHandler($this->getLogFilePath(), Logger::WARNING));

        return true;
    }

    /**
     * @return string
     */
    public function getLogFilePath() : string
    {
        $logsDir = $this->_containerInterface->getParameter('kernel.logs_dir');

        return $logsDir . '/' . $this->getLogFileName();
    }

    /**
     * @return string
     */
    public function getLogFileName() : string
    {
        $env = $this->_containerInterface->getParameter('kernel.environment');

        return  $this->_fileName . '_' . $env . '.log';
    }

    /**
     * @param string $fileName
     * @return LoggerInterface
     */
    /** @noinspection PhpUndefinedClassInspection */
    public function getLogger($fileName = 'mediaconverter') : LoggerInterface
    {
        $this->_fileName = $fileName;

        $this->buildLogger();

        return $this->_logger;
    }
}