<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.12.16
 * Time: 20:02
 */

namespace Mittax\MediaConverterBundle\Tests\Storage;

use Mittax\MediaConverterBundle\Entity\Storage\IStorageItem;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class StorageItemTest
 * @package Mittax\MediaConverterBundle\Tests\Storage
 */
class StorageItemTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testStorageItemInstance()
    {
        $fileStorageItem = new StorageItem($this->_flySystemItemMock);

        $imageMetadata = $this->_flySystemItemMock;

        $this->assertNotEmpty($imageMetadata);

        $this->assertInstanceOf(IStorageItem::class, $fileStorageItem);

        $this->assertEquals($imageMetadata['path'], $fileStorageItem->getPath());

        $this->assertEquals($imageMetadata['basename'], $fileStorageItem->getBasename());

        $this->assertEquals($imageMetadata['size'], $fileStorageItem->getSize());

        $this->assertEquals($imageMetadata['type'], $fileStorageItem->getType());

        $this->assertEquals($imageMetadata['dirname'], $fileStorageItem->getDirname());

        $this->assertEquals($imageMetadata['extension'], $fileStorageItem->getExtension());

        $this->assertEquals($imageMetadata['filename'], $fileStorageItem->getFilename());

        $this->assertEquals($imageMetadata['timestamp'], $fileStorageItem->getTimestamp());

        $this->assertNotEmpty($fileStorageItem->getUuid());

        $this->assertEquals($imageMetadata, $fileStorageItem->getRawData());
    }
}