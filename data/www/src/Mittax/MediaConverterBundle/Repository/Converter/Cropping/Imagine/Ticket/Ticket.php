<?php

namespace Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;
use Mittax\MediaConverterBundle\ValueObjects\BrowserImageData;
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
     * @var BrowserImageData
     */
    private $browserImageData;

    /**
     * @var string
     */
    private $sourceImagePath='';

    /**
     * @var string
     */
    private $targetPath='';
    /**
     * Ticket constructor.
     * @param StorageItem $storageItem
     * @param CroppingData $croppingData
     * @param BrowserImageData $browserImageData
     */
    public function __construct(StorageItem $storageItem, CroppingData $croppingData, BrowserImageData $browserImageData)
    {
        $this->storageItem = $storageItem;
        
        $this->croppingData = $croppingData;

        $this->browserImageData = $browserImageData;

        $this->sourceImagePath = Filesystem::getStoragePath($this->storageItem,'tiff');

        $targetFolder = Filesystem::createStorageFolder($this->storageItem);

        $fileName = Filesystem::getHiresCroppingFilename($this->storageItem, $this->croppingData);

        $this->targetPath = $targetFolder . '/' . $fileName;
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

    /**
     * @return BrowserImageData
     */
    public function getBrowserImageData() : BrowserImageData
    {
        return $this->browserImageData;
    }

    /**
     * @return string
     */
    public function getSourceImagePath() : string
    {
        return $this->sourceImagePath;
    }

    /**
     * @return string
     */
    public function getTargetPath() : string
    {
        return $this->targetPath;
    }
}