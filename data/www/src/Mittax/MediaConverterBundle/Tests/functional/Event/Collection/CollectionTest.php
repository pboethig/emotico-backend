<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\Thumbnail;

use Mittax\MediaConverterBundle\Event\Collection\Created;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\ObjectCollection\ICollection;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class CollectionTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $storageItemCollection = new \Mittax\MediaConverterBundle\Collection\StorageItem([]);

        $event = new Created($storageItemCollection);

        $collection = $event->getCollection();

        $this->assertInstanceOf(ICollection::class, $collection );

        $event = Dispatcher::getInstance()->dispatch('collection.created', $event);

        $this->assertInstanceOf(Event::class, $event);
    }
}