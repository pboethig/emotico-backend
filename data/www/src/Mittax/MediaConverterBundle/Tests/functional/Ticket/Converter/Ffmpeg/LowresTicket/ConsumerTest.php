<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 09.01.17
 * Time: 11:07
 */

namespace Mittax\MediaConverterBundle\Tests\Ticket\Converter\Ffmpeg\ThumbnailTicket;


use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket\Consumer;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class ConsumerTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $consumer = new Consumer([]);

        $this->assertInstanceOf(Consumer::class, $consumer);

        $this->assertEquals('ffmpeg.lowres', $consumer->getExchangeConfigurationTag());

        $this->assertEquals('\Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket\Executor', $consumer->getExecutorClassName());

    }

}