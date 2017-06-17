<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:01
 */

namespace Mittax\MediaConverterBundle\Repository\Storage;

use Mittax\MediaConverterBundle\Collection\StorageItem as ItemCollection;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\IRepository;
use Mittax\MediaConverterBundle\Repository\IRepositoryConfiguration;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Service\Uuid\StorageItemRepositoryConfig;
/**
 * Class ItemRepository
 * @package Mittax\MediaConverterBundle\Repository\Storage
 */
class ItemRepository implements IRepository
{
    /**
     * @var \Mittax\MediaConverterBundle\Collection\StorageItem
     */
    private $_collection;

    /**
     * @var StorageItemRepositoryConfig
     */
    private $_storageRepositoryConfig;

    /**
     * ItemRepository constructor.
     * @param IRepositoryConfiguration $config
     */
    public function __construct(IRepositoryConfiguration $config)
    {
        $this->_storageRepositoryConfig = $config;

        $this->_collection = $config->buildCollection();
    }

    /**
     * @param string $storagePath
     * @param string $rootPath
     * @return StorageItem
     */
    public static function getByPath(string $storagePath, $rootPath = 'storage') : StorageItem
    {
        $metadata = Filesystem::getCachedAdapter($rootPath)->getMetadata($storagePath);

        return new StorageItem($metadata);
    }


    /**
     * @param string $filename
     * @return ItemCollection
     */
    public function filterByFilename( string $filename) : ItemCollection
    {
        return $this->_collection->filterByPropertyNameAndValue('filename', $filename);
    }

    /**
     * @return \Mittax\MediaConverterBundle\Collection\StorageItem
     */
    public function filterHighRes()
    {
        return $this->_collection->filterValueLike('filename', 'highres');
    }


    /**
     * @return StorageItem
     */
    public function getFirstItem() : StorageItem
    {
        return $this->_collection->getFirstItem();
    }

    /**
     * @return StorageItem[]
     */
    public function getAllItems() : Array
    {
        return $this->_collection->getAllItems();
    }

    /**
     * @return ItemCollection
     */
    public function getCollection() : ItemCollection
    {
        return $this->_collection;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return $this->_collection->count();
    }
}