<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 21:03
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Thumbnail\Imagine;

use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Converter;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\IConverter;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\ConverterConfig;

/**
 * Class ThumbnailTest
 * @package Mittax\MediaConverterBundle\Tests\Converter\Imagine
 */
class ThumbnailTest extends AbstractKernelTestCase
{
    /**
     * @var ConverterConfig
     */
    protected $_converterConfig;

    /**
     * @var IConverter
     */
    protected $_imagineThumbnailConverter;

    public function setUp()
    {
        parent::setUp();

        $this->_converterConfig = new ConverterConfig(Config::getConverters()['imagine']);

        $this->_converterConfig->setStorageRepositoryConfig($this->_storageRepositoryConfig);

        $this->_imagineThumbnailConverter  = new Converter($this->_converterConfig);
    }

    public function testImagineThumbnailInstance()
    {
        $this->assertInstanceOf(IConverter::class, $this->_imagineThumbnailConverter);
    }

    public function testGetMetadataThumbnail()
    {
        $filesystemAdapter = Filesystem::getCachedAdapter($this->_storagePath);

        $this->assertNotEmpty($this->_testPathList[0]);

        $imageMetadata = $filesystemAdapter->getMetadata($this->_testPathList[0]);

        $this->assertNotEmpty($imageMetadata);
    }
}