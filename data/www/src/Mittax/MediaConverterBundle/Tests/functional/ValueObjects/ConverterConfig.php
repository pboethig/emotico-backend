<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 11:46
 */

namespace Mittax\MediaConverterBundle\Tests\ValueObjects;

use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\ConverterConfig;
use Mittax\MediaConverterBundle\ValueObjects\Format;

class ConverterConfigTest extends AbstractKernelTestCase
{
    /**
     * @var ConverterConfig
     */
    private $_config;
    /**
     * @var array
     */
    private $_converterData;

    public function setUp()
    {
        parent::setUp();

        $this->_converterData = Config::getConverters()['ffmpeg'];

        $this->_config = new ConverterConfig($this->_converterData);
    }

    public function testFormatInstance()
    {
        $this->assertInstanceOf(ConverterConfig::class, $this->_config);
    }

    public function testFormatMethods()
    {
        $this->assertEquals($this->_converterData['name'], $this->_config->getName());

        $this->assertEquals($this->_converterData['version'], $this->_config->getVersion()->getVersionString());

        $this->assertEquals($this->_converterData['executable'], $this->_config->getExecutable());

        $this->assertEquals($this->_converterData['thumbnailConverterClassName'], $this->_config->getThumbnailConverterClassName());

        $this->assertInstanceOf(Format::class, $this->_config->getFormats()[0]);
    }


    public function testValidateTrue()
    {
        $this->assertTrue($this->_config->validate(['name'=>'sdasdasd']));
    }

    /**
     * @expectedException  \Mittax\MediaConverterBundle\Exception\InvalidConverterConfigException
     */
    public function testValidate()
    {
        $this->_config->validate([]);    
    }

    public function testGetTempFolder()
    {
        $this->assertNotEmpty($this->_config->getTempFolderPath());
    }
}