<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 16:41
 */

namespace Mittax\RabbitMQBundle\Service\Connection;

use Mittax\RabbitMQBundle\Exception\NoConnectionTypeAvailableForThisConfigurationException;
use Mittax\RabbitMQBundle\Exception\RabbitMQConfigNotFoundException;
use Mittax\RabbitMQBundle\Service\Connection\Configuration\Configuration;
use \Mittax\RabbitMQBundle\Service\Connection\Configuration\Factory as ConfigurationFactory;
/**
 * Class Factory
 * @package Mittax\RabbitMQBundle\Service\Connection
 */
class Factory
{
    /**
     * @var IConnection[]
     */
    private $_connectionPool;

    /**
     * Builds Connectionpool
     *
     * @param ConfigurationFactory $configurationFactory
     */
    public function __construct( ConfigurationFactory $configurationFactory )
    {
        $this->buildConnectionPool($configurationFactory);
    }

    /**
     * Inspects connectionconfig and builds the connectionpool from this information
     *
     * @param ConfigurationFactory $configurationFactory
     */
    public function buildConnectionPool( ConfigurationFactory $configurationFactory )
    {
        $connections = $configurationFactory->getCollection();

        foreach ($connections as $connectionConfiguration)
        {
            $connection = $this->resolveConnection($connectionConfiguration);

            $this->_connectionPool[$connectionConfiguration->getName()] = $connection;
        }
    }

    /**
     * @param Configuration $connectionConfiguration
     * @return IConnection
     */
    public function resolveConnection(Configuration $connectionConfiguration)
    {
        $isLazy = $connectionConfiguration->isLazy();

        $isUseSockets = $connectionConfiguration->isUseSockets();

        /**********************************************************************************************7
         * Stream Connections
         *
         * SSL is only available on dedicated SSLConnection (Streamconnection only)
         */
        if ( $connectionConfiguration->isSSLVerifyPeer() )
        {
            return new SSLConnection($connectionConfiguration);
        }

        /**********************************************************************************************
         * Lazy StreamConnection
         */
        else if ( $isLazy && !$isUseSockets )
        {
            return new StreamLazyConnection($connectionConfiguration);
        }

        /**
         * Default stream connection
         */
        else if ( !$isLazy && !$isUseSockets )
        {
            return new StreamConnection($connectionConfiguration);
        }

        /**********************************************************************************************
         * Sockets
         *
         * Lazy Socket Connection (prefered)
         */
        else if ( $isLazy && $isUseSockets )
        {
            return new SocketLazyConnection($connectionConfiguration);
        }
        /**
         * Default Socket Connection
         */
        else if ( !$isLazy && $isUseSockets )
        {
            return new SocketConnection($connectionConfiguration);
        }

        throw new NoConnectionTypeAvailableForThisConfigurationException('No connection available for : ' . $connectionConfiguration->getName());
    }

    /**
     * @return \AMQPConnection[]
     */
    public function getConnectionPool()
    {
        return $this->_connectionPool;
    }

    /**
     * @param $connectionName
     * @return IConnection
     */
    public function getConnectionByName($connectionName)
    {
        foreach ($this->_connectionPool as $connection)
        {
            if ($connection->getName() == $connectionName)
            {
                return $connection;
            }
        }

        throw new RabbitMQConfigNotFoundException('No config: "' . $connectionName . '" found.');
    }

    /**
     * @param IConnection $connection
     * @return string
     */
    public function getConnectionType(IConnection $connection)
    {
        $className = get_class($connection);

        $parts = explode('\\', $className);

        return end($parts);
    }
}