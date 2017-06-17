<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.01.17
 * Time: 10:27
 */

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Exception\MetadataTempFileCouldNotBeCreatedException;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Service\System\Config;

/**
 * Class MetadataGeneratorAbstract
 * @package Mittax\MediaConverterBundle\Service\Metadata\Generator
 */
abstract class MetadataGeneratorAbstract implements IMetadataGenerator
{
    /**
     * @var array
     */
    protected $_supportedFormats;

    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * @var string
     */
    protected $_tempFilePath;

    /**
     * Reader constructor.
     * @param StorageItem $storageItem
     */
    public function __construct(StorageItem $storageItem)
    {
        $this->_storageItem = $storageItem;
    }

    /**
     * @param bool $createIfNotExists
     * @return array|false|mixed
     */
    public function read($createIfNotExists = true) : Array
    {
        $storagePath = $this->getStoragePath();

        $cachedAdapter = Filesystem::getCachedAdapter('storage');

        if ($cachedAdapter->has($storagePath))
        {
            return $cachedAdapter->read($storagePath);
        }

        return [];
    }


    /**
     * @return string
     */
    public function copyAssetToTempFolder() : string
    {
        $this->_tempFilePath = $this->buildTempFilePath();

        $file = Filesystem::getCachedAdapter('storage')->read($this->_storageItem->getPath());

        @file_put_contents($this->_tempFilePath, $file['contents']);

        if (!file_exists($this->_tempFilePath))
        {
            throw new MetadataTempFileCouldNotBeCreatedException($this->_tempFilePath);
        }

        return $this->_tempFilePath;
    }

    /**
     * @return string
     */
    public function storeMetadataAsJsonFile() : string
    {
        $this->copyAssetToTempFolder();

        $exif = $this->extractMetadataFromTempFile();

        $json = json_encode($exif->getRawData());

        $path = $this->getStoragePath();

        Filesystem::getCachedAdapter('storage')->write($path, $json, new \League\Flysystem\Config());

        return $path;
    }

    /**
     * @return string
     */
    public function getStoragePath() : string
    {
        return 'assets/'.$this->_storageItem->getUuid() . '_exif.json';
    }

    /**
     * @return string
     */
    public function buildTempFilePath() : string
    {
        $root = __DIR__ . '/../../../../../../';

        return $root . Config::getPaths()['temp'] . '/' . uniqid() . '_' . $this->_storageItem->getFilename() . '.'.$this->_storageItem->getExtension();
    }

    /**
     * @return bool
     */
    public function doesSupportsFormat() : bool
    {
        return in_array($this->_storageItem->getExtension(), $this->_supportedFormats);
    }
}