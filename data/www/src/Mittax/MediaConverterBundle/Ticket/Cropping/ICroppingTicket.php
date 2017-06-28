<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 13:40
 */

namespace Mittax\MediaConverterBundle\Ticket\Cropping;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\ValueObjects\BrowserImageData;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;

/**
 * Interface ICroppingTicket
 * @package Mittax\MediaConverterBundle\Ticket\Thumbnail
 */
interface ICroppingTicket extends ITicket
{
    /**
     * @return StorageItem
     */
    public function getStorageItem() : StorageItem;

    /**
     * @return CroppingData
     */
    public function getCroppingData() : CroppingData;

    /**
     * @return BrowserImageData
     */
    public function getBrowserImageData() : BrowserImageData;

}