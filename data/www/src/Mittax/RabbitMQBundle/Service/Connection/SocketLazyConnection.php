<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 18:03
 */

namespace Mittax\RabbitMQBundle\Service\Connection;
use PhpAmqpLib\Connection\AMQPLazySocketConnection;

/**
 * Class LazySocketConnection
 * @package Mittax\RabbitMQBundle\Service\Connection
 */
class SocketLazyConnection extends AbstractConnection
{
    /**
     * @return AMQPLazySocketConnection
     */
    public function getConnection()
    {
        return new AMQPLazySocketConnection(
            $this->_configuration->getHost(),
            $this->_configuration->getPort(),
            $this->_configuration->getUsername(),
            $this->_configuration->getPassword(),
            $this->_configuration->getVhost(),
            $this->_configuration->isInsist(),
            $this->_configuration->getLoginMethod(),
            $this->_configuration->getLoginResponse(),
            $this->_configuration->getLocale(),
            $this->_configuration->getConnectionTimeout(),
            $this->_configuration->isKeepAlive()
        );
    }
}