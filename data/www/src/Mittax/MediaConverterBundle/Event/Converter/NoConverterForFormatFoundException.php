<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 10:30
 */

namespace Mittax\MediaConverterBundle\Event\Converter;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Symfony\Component\EventDispatcher\Event;


/**
 * Class NoConverterForFormatFoundExceptionextends
 * @package Mittax\MediaConverterBundle\Event\Converter
 */
class NoConverterForFormatFoundException extends Event
{
    const NAME = 'no.converter.for.format.found.exception';

    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * NoConverterForFormatFoundException constructor.
     * @param StorageItem $storageItem
     */
    public function __construct(StorageItem &$storageItem)
    {
        $this->_storageItem = $storageItem;
    }

    /**
     * @return StorageItem
     */
    public function getStorageItem()
    {
        return $this->_storageItem;
    }
}