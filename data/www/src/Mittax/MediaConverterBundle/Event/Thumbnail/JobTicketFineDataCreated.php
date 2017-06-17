<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Thumbnail;


use Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class JobTicketFineDataCreated extends Event
{
    const NAME = 'thumbnail.jobticket.finedata.created';

    protected $_jobTicket;

    /**
     * @var Thumbnail
     */
    protected $_thumbnail;

    public function __construct(ITicket $jobTicket)
    {
        $this->_jobTicket = $jobTicket;
    }


    /**
     * @return IThumbnailTicket
     */
    public function getJobTicket()
    {
        return $this->_jobTicket;
    }
}