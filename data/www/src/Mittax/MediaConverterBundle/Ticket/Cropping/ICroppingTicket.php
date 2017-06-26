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

}