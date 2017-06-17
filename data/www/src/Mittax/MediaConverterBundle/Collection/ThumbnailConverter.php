<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:10
 */

namespace Mittax\MediaConverterBundle\Collection;

use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\IConverter;
use Mittax\ObjectCollection\CollectionAbstract;

/**
 * Class StorageItem
 * @package Mittax\MediaConverterBundle\Collection
 */
class ThumbnailConverter extends CollectionAbstract
{
    /**
     * @return IConverter
     */
    public function getFirstItem() : IConverter
    {
        return parent::getFirstItem();
    }

    /**
     * @return IConverter
     */
    public function getLastItem(): IConverter
    {
        return parent::getLastItem();
    }

    /**
     * @return IConverter[]
     */
    public function getAllItems() : Array
    {
        return parent::getAllItems();
    }
}