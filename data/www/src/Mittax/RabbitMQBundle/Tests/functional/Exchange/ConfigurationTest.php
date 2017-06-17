<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 10.12.16
 * Time: 22:46
 */

namespace Mittax\RabbitMQBundle\Test\Exchange;


use Mittax\RabbitMQBundle\Service\Exchange\Configuration;
use Mittax\RabbitMQBundle\Tests\RequestAbstract;

/**
 * Class ConfigurationTest
 * @package Mittax\RabbitMQBundle\Test\Exchange
 */
class ConfigurationTest extends RequestAbstract
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testConfigurationType()
    {
        $exchangeDefinition = [
            'name' => 'testexchangetest',
            'queueName' => 'q_testexchangetest',
            'type' => 'fanout',
            'auto_delete' => false,
            'durable' => true,
            'passive' => false,
            'parameters' => [],
            'nowait'=>false,
            'exclusive'=>false
        ];

        $configuration = new Configuration($exchangeDefinition);

        $this->assertEquals($exchangeDefinition['name'], $configuration->getName());
        $this->assertEquals($exchangeDefinition['queueName'], $configuration->getQueueName());
        $this->assertEquals($exchangeDefinition['type'], $configuration->getType());
        $this->assertEquals($exchangeDefinition['auto_delete'], $configuration->isAutoDelete());
        $this->assertEquals($exchangeDefinition['durable'], $configuration->isDurable());
        $this->assertEquals($exchangeDefinition['passive'], $configuration->isPassive());
        $this->assertEquals($exchangeDefinition['parameters'], $configuration->getParameters());
        $this->assertEquals($exchangeDefinition['nowait'], $configuration->isNowait());
        $this->assertEquals($exchangeDefinition['exclusive'], $configuration->isExclusive());
    }
}