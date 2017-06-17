<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 18:09
 */

namespace Mittax\RabbitMQBundle\Service\Connection;


use Mittax\RabbitMQBundle\Service\Connection\Configuration\Configuration;
use PhpAmqpLib\Connection\AbstractConnection as AMQPBAbstractConnection;

/**
 * Interface IConnection
 * @package Mittax\RabbitMQBundle\Service\Connection
 */
interface IConnection
{
    /**
     * IConnection constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration);

    /**
     * @return AMQPBAbstractConnection
     */
    public function getConnection();

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return Configuration
     */
    public function getConfiguration() : Configuration;
}