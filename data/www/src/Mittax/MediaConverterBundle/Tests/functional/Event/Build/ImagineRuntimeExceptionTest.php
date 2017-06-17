<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\Thumbnail;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Event\Builder\ImagineRuntimeException;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class ImagineRuntimeExceptionTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $event = new ImagineRuntimeException(new \Imagine\Exception\RuntimeException('a testmessage from unittest'), new StorageItem($this->_flySystemItemMock));

        $this->assertInstanceOf(StorageItem::class, $event->getTicket());

        $event = Dispatcher::getInstance()->dispatch(ImagineRuntimeException::NAME, $event);

        $this->assertInstanceOf(Event::class, $event);
    }
}