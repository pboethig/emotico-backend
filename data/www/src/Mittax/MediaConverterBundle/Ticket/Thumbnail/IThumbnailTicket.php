<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 13:40
 */

namespace Mittax\MediaConverterBundle\Ticket\Thumbnail;


use Imagine\Image\Box;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\OutputFormatAbstract;
use Mittax\MediaConverterBundle\Ticket\ITicket;

interface IThumbnailTicket extends ITicket
{
    /**
     * @return string
     */
    public function getCurrentTempFilePath(): string ;

    /**
     * @return string
     */
    public function getCurrentTargetStoragePath(): string;

    /**
     * @return string
     */
    public function setCurrentTargetStoragePath(string $storagePath);

    /**
     * @return StorageItem
     */
    public function getStorageItem() : StorageItem;

    /**
     * @return OutputFormatAbstract
     */
    public function getCurrentOutputFormat();

    /**
     * @return Box
     */
    public function getCurrentBox();

    public function getCurrentMode();

    public function getCurrentSize();
}