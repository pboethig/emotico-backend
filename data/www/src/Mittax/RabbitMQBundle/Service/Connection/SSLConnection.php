<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 18:03
 */

namespace Mittax\RabbitMQBundle\Service\Connection;

use PhpAmqpLib\Connection\AMQPSSLConnection;

class SSLConnection extends AbstractConnection
{
    /**
     * @return AMQPSSLConnection
     */
    public function getConnection()
    {
        return new AMQPSSLConnection(
            $this->_configuration->getHost(),
            $this->_configuration->getPort(),
            $this->_configuration->getUsername(),
            $this->_configuration->getPassword(),
            $this->_configuration->getVhost(),
            $this->_configuration->getSSLOptionArray()
        );
    }
}