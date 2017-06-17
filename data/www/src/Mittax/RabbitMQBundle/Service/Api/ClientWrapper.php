<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 20:25
 */

namespace Mittax\RabbitMQBundle\Service\Api;

use Mittax\RabbitMQBundle\Service\Connection\Factory;
use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use Symfony\Component\DependencyInjection\ContainerInterface;

use RabbitMq\ManagementApi\Client;

class ClientWrapper
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Factory
     */
    protected $connectionFactory;

    /**
     * @var IConnection
     */
    protected $connection;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \Mittax\RabbitMQBundle\Service\Connection\Configuration\Configuration
     */
    protected $configuration;

    /**
     * Queue constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->connectionFactory = $this->container->get('mittax_rabbitmq.service.connection.factory');

        $this->connection = $this->connectionFactory->getConnectionByName('default');

        $this->configuration = $this->connection->getConfiguration();

        $this->buildClient();
    }

    private function buildClient()
    {
        $baseUrl = $this->getBaseUrl();

        $this->client = new Client(null, $baseUrl,$this->configuration->getUsername(), $this->configuration->getPassword());
    }

    /**
     * @return string
     */
    private function getBaseUrl()
    {
        $protocoll = 'http://';

        if ($this->configuration->isSSLVerifyPeer())
        {
            $protocoll='https://';
        }

        return $protocoll.$this->configuration->getHost().":".$this->configuration->getApiPort();
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}