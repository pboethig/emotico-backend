<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\Converter;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Event\Converter\NoConverterForFormatFoundException;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class NoConverterForFormatFoundExceptionTest
 * @package Mittax\MediaConverterBundle\Tests\Event\Converter
 */
class NoConverterForFormatFoundExceptionTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $storageItem = new StorageItem($this->_flySystemItemMock);

        $event = new NoConverterForFormatFoundException($storageItem);

        $this->assertInstanceOf(StorageItem::class, $event->getStorageItem());

        $event = Dispatcher::getInstance()->dispatch(NoConverterForFormatFoundException::NAME, $event);
    }
}