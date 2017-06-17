<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:36
 */

namespace Mittax\MediaConverterBundle\Tests\Collection;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class StorageItemTest extends AbstractKernelTestCase
{
    /**
     * @var int
     */
    private $_repeats = 100;

    protected $_isBlackFireTracked = false;

    protected $uuids;

    public function setUp()
    {
        parent::setUp();
    }

    public function testFindByFilenameInstance()
    {
        $items=[];

        $fileStorageItem = null;

        for ($i = 0; $i < $this->_repeats; $i++)
        {
            $fileStorageItem = null;

            $fileStorageItem = new StorageItem($this->getFlySystemItemMock($i));

            $uuids[] =  $fileStorageItem->getUuid();

            $items[] = $fileStorageItem;
        }

        $collection = new \Mittax\MediaConverterBundle\Collection\StorageItem($items);

        /**
         * test last item
         */
        $this->assertEquals($fileStorageItem->getUuid(), $collection->getLastItem()->getUuid());

        /**
         * test firstItem
         */
        $this->assertEquals($uuids[0], $collection->getFirstItem()->getUuid());

        /**
         * Test count after loop
         */
        $this->assertEquals($this->_repeats, $collection->count());

        /**
         * Test filtering
         */
        $filteredCollection = $collection->filterByPropertyNameAndValue('filename', $collection->getFirstItem()->getFilename());
        $this->assertEquals(1, $filteredCollection->count());
    }


    public function testCollectionInstance()
    {
        $fileStorageItem = new StorageItem($this->getFlySystemItemMock(1));

        $fileStorageItem1 = new StorageItem($this->getFlySystemItemMock(2));

        $items = [$fileStorageItem, $fileStorageItem1];

        $collection = new \Mittax\MediaConverterBundle\Collection\StorageItem($items);

        $this->assertEquals(2, $collection->count());
    }

    public function testFilterByValueLike()
    {
        /**
         * lowres
         */
        $fileStorageItem = new StorageItem($this->getFlySystemItemMock(1, 'lowres'));

        /**
         * highres
         */
        $fileStorageItem2 = new StorageItem($this->getFlySystemItemMock(2));

        $fileStorageItem3 = new StorageItem($this->getFlySystemItemMock(3));

        $fileStorageItem4 = new StorageItem($this->getFlySystemItemMock(4));

        $items = [$fileStorageItem, $fileStorageItem2, $fileStorageItem3, $fileStorageItem4];

        $collection = new \Mittax\MediaConverterBundle\Collection\StorageItem($items);

        //filter only highres
        $filteredCollection = $collection->filterValueLike('filename', 'highres');

        $this->assertCount(3, $filteredCollection);

        $filteredCollection = $collection->filterValueLike('filename', 'lowres');

        $this->assertCount(1, $filteredCollection);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}