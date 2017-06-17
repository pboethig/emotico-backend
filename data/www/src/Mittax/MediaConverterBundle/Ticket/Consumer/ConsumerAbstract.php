<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 13:19
 */

namespace Mittax\MediaConverterBundle\Ticket\Consumer;


use Mittax\MediaConverterBundle\Ticket\Executor\IExecutor;
use Mittax\MediaConverterBundle\Ticket\MessageQueue\AbstractRequest;
use Mittax\RabbitMQBundle\Service\Consumer\Type\Direct;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class ConsumerAbstract
 * @package Mittax\MediaConverterBundle\ThumbnailTicket\Consumer
 */
abstract class ConsumerAbstract extends AbstractRequest
{
    /**
     * @var string
     */
    protected $_executorClassName = '';
    
    /**
     * @return bool
     */
    public function execute() : bool
    {
            $consumer = new Direct($this->_request);

            $consumer->execute();

        return true;
    }

    /**
     * @return callable
     */
    public function getReturnCallback() : callable
    {
        return function ($replyCode, $replyText, $exchange, $routingKey, AMQPMessage $message)
        {
            $ticket = unserialize($message->body);

            /**  @var $executor IExecutor */
            $executor = new $this->_executorClassName($ticket);

            $executor->execute();

            gc_collect_cycles();

            return false;
        };
    }

    /**
     * @return string
     */
    public function getExecutorClassName()
    {
        return $this->_executorClassName;
    }

    /**
     * @return string
     */
    public function getExchangeConfigurationTag()
    {
        return $this->_exchangeConfigurationTag;
    }
}