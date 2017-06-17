<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 08.12.16
 * Time: 20:01
 */

namespace Mittax\MediaConverterBundle\Tests\Logger;
    
use Mittax\MediaConverterBundle\Service\Logger\Factory;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Psr\Log\LoggerInterface;

class LoggerTest extends AbstractKernelTestCase
{
    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * @var Factory
     */
    private $_loggerFactory;

    public function setUp()
    {
        parent::setUp();

        /** @var  $loggerFactory Factory*/
        $this->_loggerFactory = $this->container->get('mittax_rabbitmq.logger.factory');

        $this->_logger = $this->_loggerFactory->getLogger('rabbitmq');
    }

    /**
     *  Test if loggerinterface is from correct type
     */
    public function testLoggerInstance()
    {
        $this->assertInstanceOf('Symfony\Bridge\Monolog\Logger', $this->_logger);

    }

    public function testBuildLogger()
    {
        $this->assertTrue($this->_loggerFactory->buildLogger());
    }

    public function testGetLogFileName()
    {
        $this->assertEquals('rabbitmq_test.log', $this->_loggerFactory->getLogFileName());
    }

    public function testLogFilePath()
    {
        $this->assertNotNull($this->_loggerFactory->getLogFilePath());

        @unlink($this->_loggerFactory->getLogFilePath());

        $filePath = $this->_loggerFactory->getLogFilePath();

        $this->_logger->alert("test log entry");

        $this->assertTrue(file_exists($filePath));

        @unlink($filePath);
    }
}