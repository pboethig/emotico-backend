<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:10
 */

namespace Mittax\MediaConverterBundle\Collection;
use Mittax\MediaConverterBundle\Entity\Metadata\MetadataItem;
use Mittax\ObjectCollection\CollectionAbstract;
use Mittax\ObjectCollection\ICollection;
/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\Collection
 */
class Metadata extends CollectionAbstract implements ICollection
{
    /**
     * @return MetadataItem
     */
    public function getFirstItem() : MetadataItem
    {
        return parent::getFirstItem();
    }

    /**
     * @return MetadataItem
     */
    public function getLastItem() : MetadataItem
    {
        return parent::getLastItem();
    }

    /**
     * @return MetadataItem[]
     */
    public function getAllItems() : Array
    {
        return parent::getAllItems();
    }
}