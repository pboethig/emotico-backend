<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:59
 */

namespace Mittax\MediaConverterBundle\Ticket\MessageQueue;

use Mittax\RabbitMQBundle\Service\Exchange\Configuration;
use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use PhpAmqpLib\Message\AMQPMessage;

interface IRequest
{
    /**
     * @return callable
     */
    public function getReturnCallback() : callable;

    /**
     * @param string $connectionName
     * @return IConnection
     */
    public function getConnectionInterface(string $connectionName) : IConnection;

    /**
     * @return Configuration
     */
    public function getConfiguration() : Configuration;

    /**
     * @return callable
     */
    public function getAcknowledgmentCallback(): callable;

    /**
     * @return callable
     */
    public function getNacknowledgmentCallback():callable;

    /**
     * @return bool
     */
    public function execute() : bool;

    /**
     * @param IJobTicket[] $jobTickets
     * @return AMQPMessage[]
     */
    public function buildMessages(Array $jobTickets) : Array;
}