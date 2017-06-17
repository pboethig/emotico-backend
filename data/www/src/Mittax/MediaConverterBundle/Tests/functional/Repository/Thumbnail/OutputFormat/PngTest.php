<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 22.12.16
 * Time: 00:31
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Thumbnail\OutputFormat;


use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\IOutputFormat;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\Png;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
/**
 * Class JpgTest
 * @package Mittax\MediaConverterBundle\Tests\Repository\Thumbnail\OutputFormat
 */
class PngTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testPng()
    {
        $outputFormat = Config::getOutputFormatsByConverterName('imagine');

        $pngFormat = new Png($outputFormat[0]);

        $this->assertInstanceOf(IOutputFormat::class, $pngFormat);

        $this->assertNotEmpty($pngFormat->getSizes());

        $this->assertNotNull($pngFormat->getAdditionalFilter());

        $this->assertNotNull($pngFormat->getFilter());

        $this->assertNotEmpty($pngFormat->getFormat());

        $this->assertNotEmpty($pngFormat->getMode());

        $this->assertNotEmpty($pngFormat->getQuality());
    }
}