<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 17:43
 */

namespace Mittax\RabbitMQBundle\Service\Exchange;
use Mittax\RabbitMQBundle\Exception\QueueDeclarePropertyNotFoundException;
use Mittax\RabbitMQBundle\Traits\Creation\Construct;

/**
 * Class DeclareDTO
 * @package Mittax\RabbitMQBundle\Service\Queue
 */
class Configuration
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_type = 'fanout';

    /**
     * @var bool
     */
    private $_passive = false;

    /**
     * the queue will survive server restarts
     *
     * @var bool
     */
    private $_durable = true;

    /**
     * the queue can be accessed in other channels
     *
     * @var bool
     */
    private $_exclusive= false;

    /**
     * the queue won't be deleted once the channel is closed.
     *
     * @var bool
     */
    private $_auto_delete= false;

    /**
     * Doesn't wait on replies for certain things
     *
     * @var bool
     */
    private $_nowait= false;

    /**
     * How you send certain extra data to the queue declare
     *
     * @var array
     */
    private $_parameters= array('delivery_mode' => 2);

    /**
     * @var bool
     */
    private $internal = false;

    /**
     * @var null
     */
    private $_ticket = null;

    /**
     * @var string
     */
    private $_queueName;

    /**
     * @var string
     */
    private $_routingKey;
    
    /**
     * Use constructor extensions to build object
     */
    use Construct;

    /**
     * DeclareDTO constructor.
     * @param array $rawConfig
     */
    public function __construct(Array $rawConfig)
    {
        $this->constructByKeyValue($rawConfig, QueueDeclarePropertyNotFoundException::class);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return boolean
     */
    public function isPassive()
    {
        return $this->_passive;
    }

    /**
     * @return boolean
     */
    public function isDurable()
    {
        return $this->_durable;
    }

    /**
     * @return boolean
     */
    public function isExclusive()
    {
        return $this->_exclusive;
    }

    /**
     * @return boolean
     */
    public function isAutoDelete()
    {
        return $this->_auto_delete;
    }

    /**
     * @return boolean
     */
    public function isNowait()
    {
        return $this->_nowait;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * @return boolean
     */
    public function isInternal()
    {
        return $this->internal;
    }

    /**
     * @return null
     */
    public function getTicket()
    {
        return $this->_ticket;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->_queueName;
    }

    /**
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->_routingKey;
    }
}