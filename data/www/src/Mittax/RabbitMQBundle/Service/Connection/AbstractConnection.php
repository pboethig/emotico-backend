<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 19:15
 */

namespace Mittax\RabbitMQBundle\Service\Connection;

use Mittax\RabbitMQBundle\Service\Connection\Configuration\Configuration;

/**
 * Class ConnectionAbstract
 * @package Mittax\RabbitMQBundle\Service\Connection
 */
abstract class AbstractConnection implements IConnection
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * @var Configuration
     */
    protected $_configuration;

    /**
     * @return \PhpAmqpLib\Connection\AbstractConnection
     */
    abstract public function getConnection();

    /**
     * SSLConnection constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->_name = $configuration->getName();

        $this->_configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->_name;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration() : Configuration
    {
        return $this->_configuration;
    }

}