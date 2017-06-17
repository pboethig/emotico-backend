<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\Thumbnail;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\Thumbnail\FineDataCreated;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Builder;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class FineDataCreatedTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $event = new FineDataCreated($this->_jobTicketFixure);

        $this->assertInstanceOf(ITicket::class, $event->getJobTicket());

        $event = Dispatcher::getInstance()->dispatch('thumbnail.finedata.created', $event);

        $this->assertInstanceOf(Event::class, $event);
    }
}