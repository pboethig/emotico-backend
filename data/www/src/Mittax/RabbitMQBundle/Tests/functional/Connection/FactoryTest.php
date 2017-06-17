<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 14:06
 */

namespace Mittax\RabbitMQBundle\Tests\Connection;

use Mittax\RabbitMQBundle\Service\Connection\IConnection;
use Mittax\RabbitMQBundle\Service\Connection\Ping;
use Mittax\RabbitMQBundle\Tests\AbstractKernelTestCase;
use PhpAmqpLib\Connection\AMQPLazySocketConnection;

/**
 * Class FactoryTest
 * @package Mittax\RabbitMQBundle\Tests\Connection
 */
class FactoryTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    /**
     * Test if factory creates a IConnection
     */
    public function testCreateFactory()
    {
        $connectionInterface = $this->_connectionFactory->getConnectionByName('default');

        $this->assertInstanceOf(IConnection::class, $connectionInterface);
    }

    public function testGetConnection()
    {
        $connectionInterface = $this->_connectionFactory->getConnectionByName('default');

        /** @var  $amqpConnection AMQPLazySocketConnection*/
        $amqpConnection = $connectionInterface->getConnection();

        $this->assertNotNull($amqpConnection);

        $this->assertEquals($connectionInterface->getName(), 'default');

        $ping = new Ping($connectionInterface);
        
        $result = $ping->performTest();

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Mittax\RabbitMQBundle\Exception\RabbitMQConfigNotFoundException
     */
    public function testGetConnectionByNameFailed()
    {
        $this->_connectionFactory->getConnectionByName('defaultsss');
    }

    public function testBuildConnectionPool()
    {
        $this->_connectionFactory->buildConnectionPool($this->_configurationFactory);

        $this->assertInstanceOf(IConnection::class, $this->_connectionFactory->getConnectionPool()['default']);
    }

    public function testResolveConnection()
    {
        $configuration = $this->_configurationFactory->getConnectionByName('default');

        $connection = $this->_connectionFactory->resolveConnection($configuration);

        $this->assertInstanceOf(IConnection::class, $connection);
    }

    public function testGetConnectionType()
    {
        $configuration = $this->_configurationFactory->getConnectionByName('default');

        $connection = $this->_connectionFactory->resolveConnection($configuration);

        $connectionType = $this->_connectionFactory->getConnectionType($connection);

        $this->assertContains('Connection', $connectionType);
    }
}