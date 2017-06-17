<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 00:31
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Format;

use Mittax\MediaConverterBundle\Repository\Format\RepositoryConfig;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class RepositoryConfigTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testRepositoryConfig()
    {
        $uui = $this->_storageRepositoryConfig->getUuid();

        $storageRepository = $this->_storageRepositoryFactory->getByUuid($uui);

        $collection = $storageRepository->getCollection();

        $storageItem = $collection->getFirstItem();

        $repositoryConfig = new RepositoryConfig($collection);

        $this->assertNotEmpty($repositoryConfig->getUuid());

        $storageCollection = $repositoryConfig->buildCollection();

        $this->assertEquals($storageCollection->getFirstItem()->getUuid(), $storageItem->getUuid());
    }
}