<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 11.12.16
 * Time: 20:18
 */

namespace Mittax\RabbitMQBundle\Exception\Handler;

use Mittax\RabbitMQBundle\Exception\ConsumerExceptionHandlerNotImplemented;

/**
 * Class Consumer
 * @package Mittax\RabbitMQBundle\Exception\Handler
 */
class Consumer
{
    /**
     * @var \Exception
     */
    private $_exception;

    /**
     * @var IConsumerException
     */
    private $_specificExceptionHandler;

    public function __construct(\Exception $e)
    {
        $this->_exception = $e;

        $thisReflection = new \ReflectionClass($this);

        $ExceptionReflection = new \ReflectionClass($e);

        $exceptionHandlerClassName = $thisReflection->getNamespaceName() . '\\' .$ExceptionReflection->getShortName();

        if (!class_exists($exceptionHandlerClassName))
        {
            throw new ConsumerExceptionHandlerNotImplemented('Handler for Exception: ' . $ExceptionReflection->getShortName() . ' not implemented');
        }

        $this->_specificExceptionHandler = new $exceptionHandlerClassName($e);
    }

    /**
     * @return IConsumerException
     */
    public function getSpecificExceptionHandler()
    {
        return $this->_specificExceptionHandler;
    }
}