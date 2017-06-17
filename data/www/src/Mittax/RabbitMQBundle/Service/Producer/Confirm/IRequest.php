<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 19:24
 */

namespace Mittax\RabbitMQBundle\Service\Producer\Confirm;

use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use Mittax\RabbitMQBundle\Service\Consumer\Configuration\Basic;
use Mittax\RabbitMQBundle\Service\Exchange\Configuration;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Interface IProducerRequest
 * @package Mittax\RabbitMQBundle\Service\Producer
 */
interface IRequest
{
    /**
     * @return callable
     */
    public function getAcknowledgementCallback() : callable;

    /**
     * @return callable
     */
    public function getNacknowledgementCallback() : callable;

    /**
     * @return callable
     */
    public function getReturnListener() : callable;

    /**
     * @return IConnection
     */
    public function getConnectionInterface() : IConnection;

    /**
     * @return AMQPMessage[]
     */
    public function getMessages() : Array;

    /**
     * @return Configuration
     */
    public function getExchangeConfiguration() : Configuration;

    /**
     * @return Basic
     */
    public function getBasicConsumeConfiguration() : Basic;
}