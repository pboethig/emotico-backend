<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:10
 */

namespace Mittax\MediaConverterBundle\Collection;
use Mittax\ObjectCollection\CollectionAbstract;
use Mittax\ObjectCollection\ICollection;
use \Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail as ThumbnailEntity;
/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\Collection
 */
class Thumbnail extends CollectionAbstract implements ICollection
{
    /**
     * @return ThumbnailEntity[]
     */
    public function getAllItems() : Array
    {
        return parent::getAllItems();
    }
}