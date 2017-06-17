<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 00:31
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Metadata;

use Mittax\MediaConverterBundle\Entity\Metadata\MetadataItem;
use Mittax\MediaConverterBundle\Repository\Metadata\ItemRepository;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class ItemRepositoryTest extends AbstractKernelTestCase
{
    /**
     * @var ItemRepository
     */
    private $_repository;

    public function setUp()
    {
        parent::setUp();

        $this->_repository = new ItemRepository($this->_metadataRepositoryConfig);
    }

    public function testCount()
    {
        $this->assertGreaterThan(0, $this->_repository->getCollection()->count());
    }

    public function testGetFirstItem()
    {
        $this->assertInstanceOf(MetadataItem::class, $this->_repository->getCollection()->getFirstItem());
    }

    public function testGetLastItem()
    {
        $this->assertInstanceOf(MetadataItem::class, $this->_repository->getCollection()->getLastItem());
    }

    public function testGetAllItems()
    {
        $this->assertNotEmpty($this->_repository->getCollection()->getAllItems());
    }

}