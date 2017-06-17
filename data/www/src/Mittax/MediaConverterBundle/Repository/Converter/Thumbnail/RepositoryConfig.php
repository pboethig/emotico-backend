<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 11:07
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail;

use Mittax\MediaConverterBundle\Collection\Format;
use Mittax\MediaConverterBundle\Collection\ThumbnailConverter;
use Mittax\MediaConverterBundle\Exception\ThumbnailConverterCLassDoesNotExistException;
use Mittax\MediaConverterBundle\Repository\IRepositoryConfiguration;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\ValueObjects\ConverterConfig;

/**
 * Class StorageRepositoryConfig
 * @package Mittax\MediaConverterBundle\Repository\Storage
 */
class RepositoryConfig implements IRepositoryConfiguration
{
    /**
     * @var string
     */
    private $_repositoryClassName = ItemRepository::class;

    /**
     * @var string
     */
    private $_uuid;

    /**
     * @var ThumbnailConverter
     */
    private static $_converterPool;

    /**
     * @var IConverter[]
     */
    private static $_converterFormatMap;

    /**
     * @var array
     */
    private $_converterConfig;

    /**
     * @var StorageRepositoryConfig
     */
    private $_storageRepositoryConfig;

    /**
     * RepositoryConfig constructor.
     * @param StorageRepositoryConfig $storageRepositoryConfig
     */
    public function __construct(StorageRepositoryConfig $storageRepositoryConfig)
    {
        $this->validate();

        $this->_storageRepositoryConfig = $storageRepositoryConfig;

        $this->_converterConfig = Config::getConverters();

        $this->_uuid = md5(json_encode($this->_converterConfig));
    }

    /**
     * @return StorageRepositoryConfig
     */
    public function getStorageRepositoryConfig()
    {
        return $this->_storageRepositoryConfig;
    }

    /**
     * @return bool
     */
    public function validate() : bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getRepositoryClassName() : string
    {
        return $this->_repositoryClassName;
    }

    /**
     * @return string
     */
    public function getUuid() : string
    {
        return $this->_uuid;
    }

    /**
     * @return Format
     */
    public function buildCollection()
    {
        $itemList = [];

        if(self::$_converterPool) return self::$_converterPool;

        foreach ($this->_converterConfig as $converterName => $configItem)
        {
            if (!class_exists($configItem['thumbnailConverterClassName']))
            {
                throw new ThumbnailConverterCLassDoesNotExistException('thumbnailConverterClassName: ' . $configItem['thumbnailConverterClassName'] . ' does not exist');
            }

            $converterConfiguration = new ConverterConfig($configItem);

            $converterConfiguration->setStorageRepositoryConfig($this->_storageRepositoryConfig);

            $converterClassName = $converterConfiguration->getThumbnailConverterClassName();

            $itemList[] = new $converterClassName($converterConfiguration);
        }

        self::$_converterPool = new ThumbnailConverter($itemList);

        return  self::$_converterPool;
    }

    /**
     * @return bool
     */
    public function buildFormatToConverterMap() : bool
    {
        if(self::$_converterFormatMap) return true;

        foreach (self::$_converterPool->getAllItems() as $converter)
        {
            foreach ($converter->getConverterConfig()->getFormats() as $format)
            {
                self::$_converterFormatMap[$format->getName()] = $converter;
            }
        }

        return true;
    }

    /**
     * @return IConverter[]
     */
    public static function getConverterFormatMap()
    {
        return self::$_converterFormatMap;
    }

    /**
     * @return array
     */
    public function getConverterConfig()
    {
        return $this->_converterConfig;
    }
}