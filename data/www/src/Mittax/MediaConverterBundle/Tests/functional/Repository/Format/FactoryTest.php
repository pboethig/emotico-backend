<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 10:18
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Format;

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
    }



    public function testGetByUuidFactory()
    {
        $this->assertNotNull("only for existence");
    }
}