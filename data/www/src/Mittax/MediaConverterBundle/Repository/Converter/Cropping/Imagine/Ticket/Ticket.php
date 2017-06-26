<?php

namespace Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
class Ticket implements ICroppingTicket
{
    /**
     * @var StorageItem
     */
    private $storageItem;

    /**
     * @var CroppingData
     */
    private $croppingData;

    /**
     * Ticket constructor.
     * @param StorageItem $storageItem
     */
    public function __construct(StorageItem $storageItem, CroppingData $croppingData)
    {
        $this->storageItem = $storageItem;
        
        $this->croppingData = $croppingData;
    }

    /**
     * @return StorageItem
     */
    public function getStorageItem() : StorageItem
    {
        return $this->storageItem;
    }

    /**
     * @return string
     */
    public function serialize() : string
    {
        return serialize($this);
    }

    /**
     * @return string
     */
    public function getJobId() : string
    {
        return $this->storageItem->getUuid();
    }

    /**
     * @return CroppingData
     */
    public function getCroppingData() : CroppingData
    {
        return $this->croppingData;
    }


}