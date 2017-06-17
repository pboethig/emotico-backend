<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 19:45
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;

/**
 * Interface IConverter
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
interface IConverter extends \Mittax\MediaConverterBundle\Repository\Converter\IConverter
{
    /**
     * @param StorageItem $thumbail
     * @return ITicketBuilder
     */
    public function createThumbnails(StorageItem $thumbail) : ITicketBuilder;

}