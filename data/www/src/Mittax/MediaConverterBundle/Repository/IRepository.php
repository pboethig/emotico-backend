<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 15.12.16
 * Time: 23:55
 */

namespace Mittax\MediaConverterBundle\Repository;
use Mittax\ObjectCollection\ICollection;
use Mittax\ObjectCollection\ICollectionItem;

/**
 * Interface IRepository
 * @package Mittax\MediaConverterBundle\Repository
 */
interface IRepository
{
    /**
     * @return ICollectionItem
     */
    public function getFirstItem() ;

    /**
     * @return ICollectionItem[]
     */
    public function getAllItems() : Array;

    /**
     * @return ICollection
     */
    public function getCollection();
}