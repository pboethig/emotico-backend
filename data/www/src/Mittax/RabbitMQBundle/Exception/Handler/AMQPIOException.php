<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 11.12.16
 * Time: 20:47
 */

namespace Mittax\RabbitMQBundle\Exception\Handler;


class AMQPIOException implements IConsumerException
{

    /**
     * @var AMQPIOException
     */
    private $_exception;

    public function __construct(\PhpAmqpLib\Exception\AMQPIOException $exception)
    {
        $this->_exception = $exception;

        $this->resolve($exception);
    }

    public function resolve(\Exception $exception) : bool
    {
        echo (PHP_EOL . 'timeout reached. No messages available. Sleep now. Inner Exception: ' . $exception->getMessage());

        return true;
    }

    /**
     * @return \Exception
     */
    public function getException() : \Exception
    {
        return $this->_exception;
    }
}