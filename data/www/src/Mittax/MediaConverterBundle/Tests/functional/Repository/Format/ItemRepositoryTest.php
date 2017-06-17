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

class ItemRepositoryTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testItemRepository()
    {

        $uui = $this->_storageRepositoryConfig->getUuid();

        $storageRepository = $this->_storageRepositoryFactory->getByUuid($uui);

        $collection = $storageRepository->getCollection();

        $repository = new RepositoryConfig($collection);

        $this->assertInstanceOf(RepositoryConfig::class, $repository);
    }
}