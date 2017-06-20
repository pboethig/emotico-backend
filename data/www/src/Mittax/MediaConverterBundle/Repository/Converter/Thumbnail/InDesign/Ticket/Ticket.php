<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 02:16
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\InDesign\Ticket;

use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\ThumbnailTicketAbstract;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
class Ticket extends ThumbnailTicketAbstract
{
    /**
     * @var ITicketBuilder
     */
    private $ticketBuilder;

    /**
     * Ticket constructor.
     * @param ITicketBuilder $builder
     */
    public function __construct(ITicketBuilder $builder)
    {
        $this->ticketBuilder = $builder;

        parent::__construct($builder);
    }

    /**
     * @return string
     */
    public function serialize() : string
    {
        $ticketPath = __NAMESPACE__. "\\".ucfirst($this->storageItem->getExtension())."\\Generator";

        $ticket = new $ticketPath($this->ticketBuilder);

        $json = $ticket->postBuild()->toJson();

        return $json;
    }
}