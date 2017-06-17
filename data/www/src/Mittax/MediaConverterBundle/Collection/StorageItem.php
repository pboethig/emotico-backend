<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:10
 */

namespace Mittax\MediaConverterBundle\Collection;

use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;
use Mittax\ObjectCollection\CollectionAbstract;

/**
 * Class StorageItem
 * @package Mittax\MediaConverterBundle\Collection
 */
class StorageItem extends CollectionAbstract implements IStorageItemCollection
{
    /**
     * @var IAdapter
     */
    private static $_cachedFilesystemAdapter;

    /**
     * @return \Mittax\MediaConverterBundle\Entity\Storage\StorageItem
     */
    public function getFirstItem() : \Mittax\MediaConverterBundle\Entity\Storage\StorageItem
    {
        return parent::getFirstItem();
    }

    /**
     * @return \Mittax\MediaConverterBundle\Entity\Storage\StorageItem
     */
    public function getLastItem(): \Mittax\MediaConverterBundle\Entity\Storage\StorageItem
    {
        return parent::getLastItem();
    }

    /**
     * @return \Mittax\MediaConverterBundle\Entity\Storage\StorageItem[]
     */
    public function getAllItems() : Array
    {
        return parent::getAllItems();
    }

    /**
     * @return IAdapter
     */
    public static function getCachedFilesystemAdapter()
    {
        return self::$_cachedFilesystemAdapter;
    }

    /**
     * @param IAdapter $cachedFilesystemAdapter
     */
    public static function setCachedFilesystemAdapter($cachedFilesystemAdapter)
    {
        self::$_cachedFilesystemAdapter = $cachedFilesystemAdapter;
    }

    /**
     * @return StorageItem
     */
    public function filterHighRes() : StorageItem
    {
        $genericCollection = parent::filterValueLike('filename', 'highres');
        
        return new self($genericCollection->getAllItems());
    }
}