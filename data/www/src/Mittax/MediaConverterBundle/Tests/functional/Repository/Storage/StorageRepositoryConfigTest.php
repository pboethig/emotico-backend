<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 11:48
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Storage;

use Mittax\MediaConverterBundle\Repository\Storage\FilesystemResolverFactory;
use Mittax\MediaConverterBundle\Repository\Storage\ItemRepository;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;
use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class StorageRepositoryConfigTest
 * @package Mittax\MediaConverterBundle\Tests\Repository\Storage
 */
class StorageRepositoryConfigTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testConfigInstance()
    {
        $this->assertNotEmpty($this->_storageRepositoryConfig->getItemPathList());
    }

    public function testGetFileSystemResolverFactory()
    {
        $this->assertNotEmpty($this->_storageRepositoryConfig->getFileSystemResolverFactory());
    }

    public function testFileSystemResolverFactoryType()
    {
        $this->assertInstanceOf(FilesystemResolverFactory::class, $this->_storageRepositoryConfig->getFileSystemResolverFactory());
    }

    /**
     * @expectedException \Mittax\MediaConverterBundle\Exception\StoragePathListNotDefinedException
     */
    public function testEmptyPathList()
    {
        $this->_storageRepositoryConfig = new StorageRepositoryConfig([]);
    }

    public function testGetCachedFilesystemAdapter()
    {
        $this->assertInstanceOf(IAdapter::class, $this->_storageRepositoryConfig->getCachedFilesystemAdapter());
    }

    public function testValidate()
    {
        $this->assertTrue($this->_storageRepositoryConfig->validate());
    }

    public function testGetRepositoryClassName()
    {
        $this->assertEquals(ItemRepository::class, $this->_storageRepositoryConfig->getRepositoryClassName());
    }

    public function testGetUuid()
    {
        $this->assertNotEmpty($this->_storageRepositoryConfig->getUuid());
    }

    public function testBuildUuid()
    {
        $this->assertNotEmpty($this->_storageRepositoryConfig->buildUuid($this->_storageRepositoryConfig));
    }
}