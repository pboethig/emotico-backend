<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 10:18
 */

namespace Mittax\MediaConverterBundle\Tests\Repository;

use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Repository\IRepository;
use Mittax\MediaConverterBundle\Repository\Storage\Factory;
use Mittax\MediaConverterBundle\Repository\Storage\ItemRepository;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class FactoryTest
 * @package Mittax\MediaConverterBundle\Tests\Repository\Storage
 */
class FactoryAbstractTest extends AbstractKernelTestCase
{
    /**
     * @var ItemRepository
     */
    protected $_storageRepository;


    public function setUp()
    {
        parent::setUp();

        $this->_storageRepository = $this->_storageRepositoryFactory->getByUuid($this->_storageRepositoryConfig->getUuid());
    }

    public function testBuildStorageRepository()
    {

        //$probe = $this->startBlackFire(1);

        for ($i=0; $i < 100;$i++)
        {
            $storageRepositoryConfig = new StorageRepositoryConfig($this->_testPathList);

            $this->_storageRepositoryFactory = new Factory($storageRepositoryConfig);

            $repository = $this->_storageRepositoryFactory->getByUuid($storageRepositoryConfig->getUuid());

            $this->assertEquals(count($this->_testPathList), $repository->getCollection()->count());

            $this->assertContains($repository->getCollection()->getFirstItem()->getBasename(), $this->_testPathList[0]);

            $this->assertContains($repository->getCollection()->getLastItem()->getBasename(), end($this->_testPathList));
        }

        //$this->stopBlackFire($probe);
    }

    public function testGetCollectionFactory()
    {
        $this->assertInstanceOf(StorageItem::class, $this->_storageRepository->getCollection());
    }

    public function testGetFirstItemFactory()
    {
        $firstItem = $this->_storageRepository->getCollection()->getFirstItem();

        $this->assertInstanceOf(\Mittax\MediaConverterBundle\Entity\Storage\StorageItem::class, $firstItem);
    }

    public function testBuild()
    {
        $this->assertTrue($this->_storageRepositoryFactory->build($this->_storageRepositoryConfig));
    }

    public function testGetRepositories()
    {
        $this->assertGreaterThan(0, $this->_storageRepositoryFactory->getRepositories());

        $storageItemRepository = $this->_storageRepositoryFactory->getRepositories();

        //test if the first repository from the list fullfills the IRepository interface
        $storageItemRepository = reset($storageItemRepository);

        $this->assertInstanceOf(IRepository::class, $storageItemRepository);
    }
}