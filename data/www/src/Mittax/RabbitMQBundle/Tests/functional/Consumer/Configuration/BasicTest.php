<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 09.12.16
 * Time: 22:32
 */

namespace Mittax\RabbitMQBundle\Test\Consumer\Configuration;

use Mittax\RabbitMQBundle\Service\Consumer\Factory;
use Mittax\RabbitMQBundle\Service\Consumer\IConsumer;
use Mittax\RabbitMQBundle\Tests\AbstractKernelTestCase;

class BasicTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetByName()
    {
        $consumerClassName = 'Mittax\RabbitMQBundle\Service\Consumer\Configuration\Basic';

        /** @var  $consumer */
        $consumer = $this->_consumerFactory->getByName($consumerClassName);

        $this->assertInstanceOf(IConsumer::class, $consumer);
    }
}