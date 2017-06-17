<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.01.17
 * Time: 12:01
 */

namespace Mittax\MediaConverterBundle\Tests\Entity;

use Mittax\MediaConverterBundle\Entity\Metadata\MetadataItem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class MetadataItemTest
 * @package Mittax\MediaConverterBundle\Tests\Entity
 */
class MetadataItemTest extends AbstractKernelTestCase
{
    /**
     * @var MetadataItem
     */
    private $_metadataItem;

    public function setUp()
    {
        parent::setUp();

        $this->_metadataItem = new MetadataItem(['test'=>'asdasdasd']);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(MetadataItem::class, $this->_metadataItem);
    }

    public function testGetRawData()
    {
        $rawData = $this->_metadataItem->getRawData();

        $this->assertNotEmpty($rawData);
    }

    public function testGetUuid()
    {
        $uuid = $this->_metadataItem->getUuid();

        $this->assertNotEmpty($uuid);
    }

    public function testToJson()
    {
        $json = $this->_metadataItem->toJson();

        $this->assertNotEmpty($json);
    }
}