<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 22.12.16
 * Time: 00:31
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Thumbnail\OutputFormat;


use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\IOutputFormat;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use \Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\Jpg;
/**
 * Class JpgTest
 * @package Mittax\MediaConverterBundle\Tests\Repository\Thumbnail\OutputFormat
 */
class JpgTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testJpg()
    {
        $outputFormat = Config::getOutputFormatsByConverterName('imagine');

        $jpgFormat = new Jpg($outputFormat[0]);

        $this->assertInstanceOf(IOutputFormat::class, $jpgFormat);

        $this->assertNotEmpty($jpgFormat->getSizes());

        $this->assertNotNull($jpgFormat->getAdditionalFilter());

        $this->assertNotNull($jpgFormat->getFilter());

        $this->assertNotEmpty($jpgFormat->getFormat());

        $this->assertNotEmpty($jpgFormat->getMode());

        $this->assertNotEmpty($jpgFormat->getQuality());
    }
}