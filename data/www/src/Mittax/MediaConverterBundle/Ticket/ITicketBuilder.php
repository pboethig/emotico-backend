<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 27.12.16
 * Time: 14:51
 */

namespace Mittax\MediaConverterBundle\Ticket;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\IConverter;

interface ITicketBuilder
{
    /**
     * @param StorageItem $storageItem
     * @return bool
     */
    public function init(StorageItem $storageItem) : bool;

    /**
     * @return bool
     */
    public function build() : bool;

    /**
     * @return mixed
     */
    public function getJobTicket();

    /**
     * @return StorageItem
     */
    public function getStorageItem();
}