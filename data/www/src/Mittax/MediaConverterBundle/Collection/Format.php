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

/**
 * Class Format
 * @package Mittax\MediaConverterBundle\Collection
 */
class Format extends CollectionAbstract implements ICollection
{
    /**
     * @return \Mittax\MediaConverterBundle\ValueObjects\Format
     */
    public function getFirstItem() : \Mittax\MediaConverterBundle\ValueObjects\Format
    {
        return parent::getFirstItem();
    }

    /**
     * @return \Mittax\MediaConverterBundle\ValueObjects\Format
     */
    public function getLastItem(): \Mittax\MediaConverterBundle\ValueObjects\Format
    {
        return parent::getLastItem();
    }

    /**
     * @return \Mittax\MediaConverterBundle\ValueObjects\Format[]
     */
    public function getAllItems() : Array
    {
        return parent::getAllItems();
    }
}