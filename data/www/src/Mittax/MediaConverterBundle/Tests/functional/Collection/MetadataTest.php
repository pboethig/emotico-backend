<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:36
 */

namespace Mittax\MediaConverterBundle\Tests\Collection;


use Mittax\MediaConverterBundle\Collection\Metadata;
use Mittax\MediaConverterBundle\Entity\Metadata\MetadataItem;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class MetadataItemTest extends AbstractKernelTestCase
{
    /**
     * @var int
     */
    private $_repeats = 100;

    /**
     * @var bool
     */
    protected $_isBlackFireTracked = false;

    /**
     * @var string
     */
    protected $uuids;

    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $collection = new Metadata([new MetadataItem(['test1'=>'asdasd']),new MetadataItem(['test2'=>'asdasd'])]);

        $this->assertNotEmpty($collection->getUuid());

        $this->assertNotEmpty($collection->getFirstItem()->getRawData());
    }
}