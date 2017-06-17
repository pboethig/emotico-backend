<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 11:46
 */

namespace Mittax\MediaConverterBundle\Tests\ValueObjects;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\Format;

class FormatTest extends AbstractKernelTestCase
{
    /**
     * @var Format
     */
    private $_format;

    public function setUp()
    {
        parent::setUp();

        $this->_format = new Format($this->_formatFixure);
    }

    public function testFormatInstance()
    {
        $this->assertInstanceOf(Format::class, $this->_format);
    }

    public function testFormatMethods()
    {
        $this->assertEquals($this->_formatFixure['name'], $this->_format->getName());

        $this->assertEquals($this->_formatFixure['type'], $this->_format->getType());

        $this->assertEquals($this->_formatFixure['extension'], $this->_format->getExtension());

        $this->assertEquals($this->_formatFixure['mimeType'], $this->_format->getMimeType());

        $this->assertNotEmpty($this->_format->getUuid());
    }
}