<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.12.16
 * Time: 14:06
 */

namespace Mittax\RabbitMQBundle\Tests\Api;


use Mittax\RabbitMQBundle\Service\Api\Queue;
use Mittax\RabbitMQBundle\Service\Connection\Configuration\Configuration;
use Mittax\RabbitMQBundle\Tests\AbstractKernelTestCase;

class QueueTest extends AbstractKernelTestCase
{
    /**
     * @var Configuration
     */
    protected $_configuration;

    public function setUp()
    {
        parent::setUp();
    }


    public function testDefaultConfiguration()
    {
        /** @var  $queueApiService Queue */
        $queueApiService = $this->container->get('mittax.rabbitmqbundle.service.api.queue');

        $this->assertNotNull($queueApiService->getClient());

        $queue = $queueApiService->getQueue('q_imagine_thumbnails');

        $this->assertNotEmpty($queue);

        $this->assertNotNull($queue['messages']);
    }
}