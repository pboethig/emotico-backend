<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 18:03
 */

namespace Mittax\RabbitMQBundle\Service\Connection;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class StreamConnection
 * @package Mittax\RabbitMQBundle\Service\Connection
 */
class StreamConnection extends AbstractConnection
{
    /**
     * @return AMQPStreamConnection
     */
    public function getConnection()
    {
        return new AMQPStreamConnection(
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
            $this->_configuration->getReadWriteTimeout(),
            $this->_configuration->getStreamContext(),
            $this->_configuration->isKeepAlive(),
            $this->_configuration->getHartBeat()
        );
    }
}