<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 11.12.16
 * Time: 20:38
 */

namespace Mittax\RabbitMQBundle\Test\Exception\Handler;


use Mittax\RabbitMQBundle\Exception\ConfigPropertyNotFoundException;
use Mittax\RabbitMQBundle\Exception\Handler\Consumer;
use Mittax\RabbitMQBundle\Tests\AbstractKernelTestCase;
use PhpAmqpLib\Exception\AMQPIOException;

class ConsumerTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test if execptionhandler is able to handle missing handlers
     *
     * @expectedException \Mittax\RabbitMQBundle\Exception\ConsumerExceptionHandlerNotImplemented
     */
    public function _testConsumerExceptionHandler()
    {
        try
        {
            throw new ConfigPropertyNotFoundException('test exception from: ' . __METHOD__);
        }
        catch (ConfigPropertyNotFoundException $e)
        {
            new Consumer($e);
        }
    }

    
    public function testAMQPIOException()
    {
        try
        {
            $message = 'Error reading data. Received 0 instead of expected 7 bytes';

            throw new AMQPIOException($message);
        }
        catch (AMQPIOException $e)
        {
            $commonExceptionHandler = new Consumer($e);

            $exceptionMessage = $commonExceptionHandler->getSpecificExceptionHandler()->getException()->getMessage();

            $this->assertEquals($exceptionMessage, $message);
        }
    }
}