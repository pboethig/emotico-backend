<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:36
 */

namespace Mittax\MediaConverterBundle\Tests\Collection;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\Format;

class FormatTest extends AbstractKernelTestCase
{
    /**
     * @var Format[]
     */
    private $_formatList;

    /**
     * @var \Mittax\MediaConverterBundle\Collection\Format
     */
    private $_formatCollection;

    public function setUp()
    {
        parent::setUp();

        $format = new Format($this->_formatFixure);

        $this->_formatList[$format->getUuid()] = $format;

        $this->_formatCollection = new \Mittax\MediaConverterBundle\Collection\Format($this->_formatList);
    }


    public function testCollectionInstance()
    {
        $this->assertInstanceOf(Format::class, $this->_formatCollection->getFirstItem());
    }

    public function testCollectionCount()
    {
        $this->assertEquals(1, $this->_formatCollection->count());
    }

    public function testGetFirstItem()
    {
        $this->assertEquals($this->_formatFixure['mimeType'], $this->_formatCollection->getFirstItem()->getMimeType());
    }

    public function testGetLastItem()
    {
        $this->assertInstanceOf(Format::class, $this->_formatCollection->getLastItem());
    }

    public function testGetAllItemsItem()
    {
        $this->assertGreaterThan(0, $this->_formatCollection->getAllItems());
    }
}