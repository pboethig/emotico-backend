<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 02:16
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket;

use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\ThumbnailTicketAbstract;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
class Ticket extends ThumbnailTicketAbstract
{
    /**
     * Ticket constructor.
     * @param ITicketBuilder $builder
     */
    public function __construct(ITicketBuilder $builder)
    {
        parent::__construct($builder);
    }

}