<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\Thumbnail;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingCreated;
use Mittax\MediaConverterBundle\Event\Dispatcher;

use Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket\Builder;
use Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket\Ticket;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class HighresCroppingCreatedTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {

        $imageMetadata = Filesystem::getCachedAdapter('storage')->getMetadata($this->_testPathList[0]);

        $storageItem = new StorageItem($imageMetadata);

        $croppingData = new \stdClass();

        $croppingData->width = 200;
        $croppingData->height = 200;
        $croppingData->top = 10;
        $croppingData->left = 10;
        $croppingData->messurement='px';

        $ticket =new Ticket($storageItem, new CroppingData($croppingData));

        $event = new HiresCroppingCreated($ticket);

        $this->assertInstanceOf(ITicket::class, $event->getJobTicket());

        $event = Dispatcher::getInstance()->dispatch($event::NAME, $event);

        $this->assertInstanceOf(Event::class, $event);
    }
}