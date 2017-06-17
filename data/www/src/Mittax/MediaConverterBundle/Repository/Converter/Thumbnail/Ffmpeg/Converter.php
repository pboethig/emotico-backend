<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 19:45
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\ThumbnailTicket\Builder;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\AbstractConverter;

/**
 * Class Converter
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg
 */
class Converter extends AbstractConverter
{
    /**
     * @param StorageItem $storageItem
     * @return ITicketBuilder
     */
    public function createThumbnails(StorageItem $storageItem) : ITicketBuilder
    {
        $builder = new Builder($storageItem);

        return $builder;
    }
}