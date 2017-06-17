<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 11:07
 */

namespace Mittax\MediaConverterBundle\Repository\Format;

use Mittax\MediaConverterBundle\Collection\Format;
use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Repository\IRepositoryConfiguration;
use Mittax\MediaConverterBundle\Service\Format\SupportedFormatsBuilder;
use Mittax\MediaConverterBundle\Service\Uuid\FormatItemRepositoryConfig;

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
     * @var StorageItem
     */
    private $_storageCollection;

    /**
     * @var string
     */
    private $_uuid;

    /**
     * @var int
     */
    private $_timeStamp;

    /**
     * RepositoryConfig constructor.
     * @param StorageItem $storageCollection
     */
    public function __construct(StorageItem $storageCollection)
    {
        $this->_storageCollection = $storageCollection;

        $this->validate();

        $this->_timeStamp = time();

        $this->_uuid = $this->buildUuid($this);
    }

    /**
     * @param RepositoryConfig $repositoryConfig
     * @return string
     */
    public function buildUuid(RepositoryConfig $repositoryConfig) : string
    {
        $generator = new FormatItemRepositoryConfig($repositoryConfig);

        return $generator->generate();
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
        $formatService = new SupportedFormatsBuilder();

        $formats = [];

        foreach ($this->_storageCollection->getAllItems() as $storageItem)
        {
            $format = $formatService->generateFormatByName($storageItem->getExtension());

            /**
             * Link storageItem and FormatItem
             */
            $format->setUuid($storageItem->getUuid());

            $formats[] = $format;
        }

        $collection = new Format($formats);

        return $collection;
    }
}