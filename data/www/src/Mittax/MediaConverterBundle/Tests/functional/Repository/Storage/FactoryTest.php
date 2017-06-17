<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 10:18
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Storage;

use Mittax\MediaConverterBundle\Repository\Storage\ItemRepository;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class FactoryTest
 * @package Mittax\MediaConverterBundle\Tests\Repository\Storage
 */
class FactoryTest extends AbstractKernelTestCase
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

    public function testGetByUuidFactory()
    {
        $this->assertInstanceOf(ItemRepository::class, $this->_storageRepository);
    }
}