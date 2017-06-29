<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 02:16
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\ThumbnailTicket;

use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\ThumbnailTicketAbstract;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
class Ticket extends ThumbnailTicketAbstract
{

    public function __construct(ITicketBuilder $builder)
    {
        parent::__construct($builder);
    }



}