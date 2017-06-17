<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 11:46
 */

namespace Mittax\MediaConverterBundle\Tests\ValueObjects;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\Size;

/**
 * Class SizeTest
 * @package Mittax\MediaConverterBundle\Tests\ValueObjects
 */
class SizeTest extends AbstractKernelTestCase
{
    /**
     * @var Size
     */
    private $_size;

    /**
     * @var array
     */
    private $_sizeFixure;

    public function setUp()
    {
        parent::setUp();

        $this->_sizeFixure = ['width'=>500, 'height'=>500];

        $this->_size = new Size($this->_sizeFixure);
    }

    public function testFormatInstance()
    {
        $this->assertInstanceOf(Size::class, $this->_size);
    }

    public function testFormatMethods()
    {
        $this->assertEquals($this->_sizeFixure['width'], $this->_size->getWidth());

        $this->assertEquals($this->_sizeFixure['height'], $this->_size->getHeight());
    }

    public function testFromSizesArray()
    {
        $this->assertNotEmpty(Size::fromSizesArray([$this->_sizeFixure]));
    }
}