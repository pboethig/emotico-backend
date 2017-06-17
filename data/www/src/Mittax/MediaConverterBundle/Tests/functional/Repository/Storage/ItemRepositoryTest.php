<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 00:31
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Storage;

use Mittax\MediaConverterBundle\Repository\Storage\ItemRepository;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class ItemRepositoryTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testItemRepository()
    {
        $repository = new ItemRepository($this->_storageRepositoryConfig);

        $this->assertGreaterThan(0, $repository->getCollection()->count());
    }
}