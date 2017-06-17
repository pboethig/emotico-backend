<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 09.01.17
 * Time: 11:07
 */

namespace Mittax\MediaConverterBundle\Tests\Ticket\Converter\Ffmpeg\ThumbnailTicket;

use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket\Producer;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class ProducerTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $producer = new Producer([]);

        $this->assertInstanceOf(Producer::class, $producer);

        $this->assertEquals('php bin/console mittax:mediaconverter:thumbnail:ffmpeg:lowres:startconsumer', $producer->buildProcess()->getCommandLine());
    }
}