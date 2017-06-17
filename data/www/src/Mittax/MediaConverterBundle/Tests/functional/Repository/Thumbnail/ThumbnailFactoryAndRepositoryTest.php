<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 21:34
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Thumbnail;

use Mittax\MediaConverterBundle\Collection\Thumbnail;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\IConverter;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Factory as thumbnailConverterFactory;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ItemRepository;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class thumbnailConverterFactoryTest
 * @package Mittax\MediaConverterBundle\Tests\Converter
 */
class ThumbnailFactoryAndRepositoryTest extends AbstractKernelTestCase
{
    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * @var ItemRepository
     */
    protected $_repository;

    /**
     * @var string
     */
    protected $_uuid;
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->_storageItem = new StorageItem($this->_flySystemItemMock);

        $this->buildConverterFactory();

        $this->_uuid = $this->_thumbnailConverterFactory->getRepositoryConfiguration()->getUuid();

        $this->_repository = $this->_thumbnailConverterFactory->getByUuid($this->_uuid);
    }

    public function testFactory()
    {
        $this->assertInstanceOf(thumbnailConverterFactory::class, $this->_thumbnailConverterFactory);
    }

    public function testGetByStorageItem()
    {
        $converter = $this->_repository->getByStorageItem($this->_storageItem);

        $this->assertInstanceOf(IConverter::class, $converter);
    }

    public function batchTestCollection()
    {
        for ($i=0;$i<=100;$i++)
        {
            $this->assertGreaterThan(1, $this->_repository->getCollection()->count());

            $this->assertEquals('ffmpeg', $this->_repository->getCollection()->getFirstItem()->getName());
        }
    }

    public function testGetByName()
    {
        $this->assertEquals('ffmpeg', $this->_repository->getByName('ffmpeg')->getName());

        $this->assertEquals('imagine', $this->_repository->getByName('imagine')->getName());
    }

    public function testCreateThumbnailsFromCollection()
    {
        $storageRepository = $this->_storageRepositoryFactory->getByUuid($this->_storageRepositoryConfig->getUuid());

        $storageCollection = $storageRepository->getCollection();

        $thumbnailData = $this->_repository->createThumbnails($storageCollection);

        /** @var  $thumbnailCollection  Thumbnail*/
        $thumbnailCollection = $thumbnailData['collection'];

        //$this->assertEquals($thumbnailCollection->count(), count($thumbnailData['jobTickets']));

        $this->assertInstanceOf(Thumbnail::class, $thumbnailCollection);

        $this->assertInstanceOf(\Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail::class, $thumbnailCollection->getFirstItem());
    }

    
}