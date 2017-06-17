<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 19:22
 */

namespace Mittax\MediaConverterBundle\Tests\Service\Format;


use Mittax\MediaConverterBundle\Service\Format\SupportedFormatsBuilder;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\Format;

class SupportedFormatsBuilderTest extends AbstractKernelTestCase
{
    /**
     * @var SupportedFormatsBuilder
     */
    protected $builder;

    public function setUp()
    {
        parent::setUp();

        $this->builder = new SupportedFormatsBuilder();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(SupportedFormatsBuilder::class, $this->builder);
    }

    public function testBuild()
    {
        $this->assertNotEmpty($this->builder->build());
    }

    public function testGetByConverterName()
    {
        $formatList = $this->builder->getByConverterName('ffmpeg');

        $this->assertNotEmpty($formatList);

        $this->assertInstanceOf(Format::class, $formatList[0]);
    }

    public function testGetFormatListByConverterName()
    {
        $formatList = $this->builder->getFormatListByConverterName('ffmpeg');

        $this->assertNotEmpty($formatList);
    }

    public function testGenerateByName()
    {
        $this->assertNotEmpty($this->builder->generateFormatByName('jpg'));
    }

    public function testGetConverterByFormat()
    {
        $formatList = $this->builder->getByConverterName('ffmpeg');

        $this->builder->getConverterByFormat($formatList[0]);

        $this->assertNotNull($this->builder);
    }
}