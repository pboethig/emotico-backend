<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 09.12.16
 * Time: 22:25
 */

namespace Mittax\RabbitMQBundle\Service\Consumer\Configuration;

use Mittax\RabbitMQBundle\Service\Consumer\IConsumer;
use Mittax\RabbitMQBundle\Traits\Creation\Construct;

class Basic implements IConsumer
{
    /**
     * @var string
     */
    private $queue = '';
    /**
     * @var string
     */
    private $consumer_tag = 'consumer';
    /**
     * no local - TRUE: the server will not send messages to the connection that published them
     *
     * @var bool
     */
    private $no_local = false;
    /**
     * no ack, false - acks turned on, true - off.  send a proper acknowledgment from the worker, once we're done with a task
     *
     * @var bool
     */
    private $no_ack = false;
    /**
     * exclusive - queues may only be accessed by the current connection
     *
     * @var bool
     */
    private $exclusive = false;
    /**
     * no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
     *
     * @var bool
     */
    private $nowait = false;
    /**
     * @var callable
     */
    private $callback = null;
    /**
     * @var null
     */
    private $ticket = null;
    /**
     * @var array
     */
    private $arguments = array();

    /**
     * @var array
     */
    private $_rawConfig;

    /**
     * @var Construct
     */
    use Construct;

    public function __construct(Array $rawData)
    {
        $this->consumer_tag .= uniqid();

        $this->_rawConfig = $rawData;

        $this->constructByKeyValue($rawData);
    }

    /**
     * @return array
     */
    public function getRawConfig() : Array
    {
        return $this->_rawConfig;
    }

    /**
     * Name of used queue
     *
     * @return string
     */
    public function getQueue() : string
    {
        return $this->queue;
    }

    /**
     * consumer tag - Identifier for the consumer, valid within the current channel. just string
     *
     * @return string
     */
    public function getConsumerTag() : string
    {
        return $this->consumer_tag;
    }

    /**
     * @return boolean
     */
    public function isNoLocal() : bool
    {
        return $this->no_local;
    }

    /**
     * @return boolean
     */
    public function isNoAck() : bool
    {
        return $this->no_ack;
    }

    /**
     * @return boolean
     */
    public function isExclusive() : bool
    {
        return $this->exclusive;
    }

    /**
     * @return boolean
     */
    public function isNowait() : bool
    {
        return $this->nowait;
    }

    /**
     * @return callable
     */
    public function getCallback() : callable
    {
        return $this->callback;
    }

    /**
     * @return int
     */
    public function getTicket() : int
    {
        return $this->ticket;
    }

    /**
     * @return array
     */
    public function getArguments() : Array
    {
        return $this->arguments;
    }
}