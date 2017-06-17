<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 11:46
 */

namespace Mittax\MediaConverterBundle\Tests\ValueObjects;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\FormatToMimeTypeList;

/**
 * Class FormatToMimeTypeTest
 * @package Mittax\MediaConverterBundle\Tests\ValueObjects
 */
class FormatToMimeTypeTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testFormatToMimeTypeInstance()
    {
        $this->assertInstanceOf(FormatToMimeTypeList::class, $this->_formatToMimeTypeList);
    }

    public function testGetByExtensionMethods()
    {
        $mimeType = $this->_formatToMimeTypeList->getByExtension('jpg');

        $this->assertNotEmpty($mimeType);

        $this->assertEquals('image/jpeg', $mimeType);
    }

    public function testAllConfiguredFormats()
    {
        $converters = $this->_mediaConverterConfig['mittax_mediaconverter']['converters'];

        $formats = $converters['ffmpeg']['formats'] . ',' . $converters['imagine']['formats'];

        $this->assertNotNull($formats);

        foreach (explode(',', $formats) as $formatName)
        {
            if (!empty($formatName))
            {
                $mimeType = $this->_formatToMimeTypeList->getByExtension(strtolower($formatName));

                $this->assertNotEmpty($mimeType);
            }
        }
    }
}