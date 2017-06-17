<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:52
 */

namespace Mittax\MediaConverterBundle\Tests\Service\Uuid;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\Service\Uuid\StorageItem as UUIDGenerator;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
/**
 * Class StorageItem
 * @package Mittax\MediaConverterBundle\Tests\Service\Uuid
 */
class StorageItemTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testGenerate()
    {
        $storageItem = new StorageItem($this->_flySystemItemMock);

        $generator = new UUIDGenerator($storageItem->getRawData());

        $this->assertInstanceOf(UUIDGenerator::class, $generator);

        $this->assertNotEmpty($generator->uuid);
    }
}