<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 10:30
 */

namespace Mittax\MediaConverterBundle\Event\Converter\Ffmpeg;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\ThumbnailTicketAbstract;
use Symfony\Component\EventDispatcher\Event;


/**
 * Class NoConverterForFormatFoundExceptionextends
 * @package Mittax\MediaConverterBundle\Event\Converter
 */
class LowresCreated extends Event
{
    const NAME = 'ffmpeg.lowres.created';

    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * @var IThumbnailTicket
     */
    protected $jobTicket;

    /**
     * LowresCreated constructor.
     * @param IThumbnailTicket $jobTicket
     */
    public function __construct(IThumbnailTicket $jobTicket)
    {
        $this->jobTicket = $jobTicket;
    }

    /**
     * @return IThumbnailTicket
     */
    public function getJobTicket()
    {
        return $this->jobTicket;
    }
}