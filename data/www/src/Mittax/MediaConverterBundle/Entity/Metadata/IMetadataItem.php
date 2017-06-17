<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.12.16
 * Time: 19:47
 */

namespace Mittax\MediaConverterBundle\Entity\Metadata;
use Mittax\ObjectCollection\ICollectionItem;

/**
 * Interface IStorageItem
 * @package Mittax\MediaConverterBundle\Entity\Metadata
 */
interface IMetadataItem extends ICollectionItem
{
    /**
     * @return string
     */
    public function getRawData() : Array;

    /**
     * @return string
     */
    public function getUuid() : string;

    /**
     * @return string
     */
    public function toJson() : string;

}