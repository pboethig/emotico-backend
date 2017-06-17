<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 22.12.16
 * Time: 18:55
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicketBuilderAbstract;

/**
 * Class Builder
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket
 */
class Builder extends ThumbnailTicketBuilderAbstract
{
     /**
     * @var string
     */
    protected $_ticketClassName = '\Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Ticket';

    /**
     * @var string
     */
    protected $_boxClassName ='\Imagine\Image\Box';

    /**
     * @return Ticket
     */
    public function getJobTicket() : Ticket
    {
        return $this->_jobTicket;
    }
}