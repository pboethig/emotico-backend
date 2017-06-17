<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 18:38
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ticket;


use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Producer;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\Process\Process;

/**
 * Class ConsumerTest
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket
 */
class ProducerTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testStartConsumerConsumer()
    {
        $instances = Config::getMediaConverterConfig()['exchangeConfiguration']['autostartConsumers']['instances'];

        $process = new Process('php bin/console mittax:mediaconverter:thumbnail-startbatchconsumers ' . $instances);

        Producer::startConsumer($process);

        $this->assertNotEmpty($process);
    }
}