<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:01
 */

namespace Mittax\MediaConverterBundle\Repository\Format;

use Mittax\MediaConverterBundle\Collection\Format;
use Mittax\MediaConverterBundle\Collection\StorageItem as ItemCollection;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\IRepository;
use Mittax\MediaConverterBundle\Repository\IRepositoryConfiguration;
use Mittax\MediaConverterBundle\Repository\RepositoryAbstract;

/**
 * Class ItemRepository
 * @package Mittax\MediaConverterBundle\Repository\Storage
 */
class ItemRepository extends RepositoryAbstract implements IRepository
{
    /**
     * @var \Mittax\MediaConverterBundle\Collection\Format
     */
    private $_collection;

    /**
     * @var IRepositoryConfiguration
     */
    private $_storageConfig;

    /**
     * ItemRepository constructor.
     * @param RepositoryConfig $config
     */
    public function __construct(RepositoryConfig $config)
    {
        $this->_storageConfig = $config;

        $this->_collection = $this->buildCollection($config);
    }

    /**
     * @param IRepositoryConfiguration $config
     * @return Format
     */
    public function buildCollection(IRepositoryConfiguration $config) : Format
    {
        return parent::buildCollection($config);
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