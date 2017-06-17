<?php

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use \PHPExif\Reader\Reader as ExifReader;
use Symfony\Component\CssSelector\Parser\Reader;

/**
 * Class Exif
 * @package Mittax\MediaConverterBundle\Service\Metadata\Generator
 */
class Exif extends MetadataGeneratorAbstract
{
    /**
     * @var array
     */
    protected $_supportedFormats = ['tif', 'tiff', 'jpg', 'jpeg'];

    /**
     * @var Reader
     */
    private $_reader;

    /**
     * Reader constructor.
     * @param StorageItem $storageItem
     */
    public function __construct(StorageItem $storageItem)
    {
        parent::__construct($storageItem);

        $this->_reader = ExifReader::factory(ExifReader::TYPE_NATIVE);
    }

    /**
     * @return \PHPExif\Exif
     */
    public function extractMetadataFromTempFile()
    {
        $reader = \PHPExif\Reader\Reader::factory(\PHPExif\Reader\Reader::TYPE_NATIVE);

        return $reader->read($this->_tempFilePath);
    }
}
