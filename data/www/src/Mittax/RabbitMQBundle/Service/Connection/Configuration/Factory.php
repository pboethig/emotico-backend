<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 14:27
 */

namespace Mittax\RabbitMQBundle\Service\Connection\Configuration;

use Mittax\RabbitMQBundle\Exception\RabbitMQConfigNotFoundException;

/**
 * Class Factory
 * @package Mittax\RabbitMQBundle\Service\Connection
 */
class Factory
{
    /**
     * @var array
     */
    private $_rawData = [];

    /**
     * @var Configuration[]
     */
    private $_configurationCollection = [];

    /**
     * Factory constructor.
     * @param array $rawData
     */
    public function __construct(Array $rawData)
    {
        $this->_rawData = $rawData;

        /**
         * @var array
         */
        foreach ($rawData as $connectionName => $connectionData)
        {
            $this->_configurationCollection[$connectionName] = new Configuration($connectionName, $connectionData);
        }
    }

    /**
     * @param $connectionName
     * @return Configuration
     */
    public function getConnectionByName($connectionName) : Configuration
    {
        if (!isset($this->_configurationCollection[$connectionName]))
        {
            throw new RabbitMQConfigNotFoundException('Configuration for connection: ' . $connectionName . ' not found');
        }

        return $this->_configurationCollection[$connectionName];
    }

    /**
     * @return Configuration
     */
    public function getDefaultConnection() : Configuration
    {
        return $this->getConnectionByName('default');
    }

    /**
     * @return Configuration[]
     */
    public function getCollection() : Array
    {
        return $this->_configurationCollection;
    }
    
    
}