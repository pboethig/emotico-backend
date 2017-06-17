<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:01
 */

namespace Mittax\MediaConverterBundle\Repository\Metadata;

use Mittax\MediaConverterBundle\Collection\Metadata;

use Mittax\MediaConverterBundle\Entity\Metadata\MetadataItem;
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
     * @return MetadataItem
     */
    public function getFirstItem() : MetadataItem
    {
        return $this->_collection->getFirstItem();
    }

    /**
     * @return MetadataItem[]
     */
    public function getAllItems() : Array
    {
        return $this->_collection->getAllItems();
    }

    /**
     * @return Metadata
     */
    public function getCollection() : Metadata
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