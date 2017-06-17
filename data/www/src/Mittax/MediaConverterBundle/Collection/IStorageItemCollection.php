<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 23.12.16
 * Time: 22:08
 */

namespace Mittax\MediaConverterBundle\Collection;


use Mittax\ObjectCollection\ICollection;

/**
 * Interface IStorageItemCollection
 * @package Mittax\MediaConverterBundle\Collection
 */
interface IStorageItemCollection extends ICollection
{
    /**
     * @return StorageItem
     */
    public function filterHighRes() : StorageItem;
}