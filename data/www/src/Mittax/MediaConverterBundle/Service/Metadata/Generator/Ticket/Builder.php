<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 22.12.16
 * Time: 18:55
 */

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\Builder\MetadataTicketBuilderAbstract;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Ticket\TicketAbstract;

/**
 * Class Builder
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket
 */
class Builder extends MetadataTicketBuilderAbstract
{
    /**
     * @var string
     */
    protected $_ticketClassName = '\Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Ticket';

    
    function init(StorageItem $storageItem) : bool
    {
        return true;
    }

    function build() : bool
    {
        $this->_jobTicket = new Ticket($this);

        return true;
    }
}