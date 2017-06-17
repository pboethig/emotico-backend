<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 20:27
 */

namespace Mittax\RabbitMQBundle\Service\Producer;

use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use Mittax\RabbitMQBundle\Service\Consumer\Configuration\Basic;
use Mittax\RabbitMQBundle\Service\Exchange\Configuration;
use Mittax\RabbitMQBundle\Service\Producer\Confirm\IRequest;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RequestAbstract
 * @package Mittax\RabbitMQBundle\Service\Producer
 */
abstract class RequestAbstract implements IRequest
{
    /**
     * @var callable
     */
    protected $_acknowledgementCallback;

    /**
     * @var callable
     */
    protected $_nacknowledgementCallback;

    /**
     * @var IConnection
     */
    protected $_connectionInterface;

    /**
     * @var AMQPMessage[]
     */
    protected $_messages;
    /**
     * @var callable
     */
    protected $_returnListenerCallback;

    /**
     * @var Configuration
     */
    protected $_ExchangeConfiguration;

    /**
     * @var string
     */
    protected $_queueName;

    /**
     * @var Basic
     */
    protected $_basicConsumeConfiguration;

    /**
     * @return callable
     */
    public function getAcknowledgementCallback() : callable
    {
        return $this->_acknowledgementCallback;
    }

    public function getNacknowledgementCallback() : callable
    {
        return $this->_nacknowledgementCallback;
    }

    /**
     * @return callable
     */
    public function getReturnListener() : callable
    {
        return $this->_returnListenerCallback;
    }

    /**
     * @return IConnection
     */
    public function getConnectionInterface() : IConnection
    {
        return $this->_connectionInterface;
    }

    /**
     * @return \PhpAmqpLib\Message\AMQPMessage[]
     */
    public function getMessages() : Array
    {
        return $this->_messages;
    }

    /**
     * @return Configuration
     */
    public function getExchangeConfiguration() : Configuration
    {
        return $this->_ExchangeConfiguration;
    }

    /**
     * @param callable $acknowledgementCallback
     */
    public function setAcknowledgementCallback($acknowledgementCallback)
    {
        $this->_acknowledgementCallback = $acknowledgementCallback;
    }

    /**
     * @param callable $nacknowledgementCallback
     */
    public function setNacknowledgementCallback($nacknowledgementCallback)
    {
        $this->_nacknowledgementCallback = $nacknowledgementCallback;
    }

    /**
     * @param IConnection $connectionInterface
     */
    public function setConnectionInterface($connectionInterface)
    {
        $this->_connectionInterface = $connectionInterface;
    }

    /**
     * @param \PhpAmqpLib\Message\AMQPMessage[] $messages
     */
    public function setMessages($messages)
    {
        $this->_messages = $messages;
    }

    /**
     * @param callable $returnListenerCallback
     */
    public function setReturnListenerCallback($returnListenerCallback)
    {
        $this->_returnListenerCallback = $returnListenerCallback;
    }

    /**
     * @param Configuration $ExchangeConfiguration
     */
    public function setExchangeConfiguration($ExchangeConfiguration)
    {
        $this->_ExchangeConfiguration = $ExchangeConfiguration;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->_queueName;
    }

    /**
     * @return Basic
     */
    public function getBasicConsumeConfiguration() : Basic
    {
        return $this->_basicConsumeConfiguration;
    }

    /**
     * @param Basic $basicConsumeConfiguration
     */
    public function setBasicConsumeConfiguration($basicConsumeConfiguration)
    {
        $this->_basicConsumeConfiguration = $basicConsumeConfiguration;
    }
}