<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 31.12.16
 * Time: 20:55
 */

namespace Mittax\MediaConverterBundle\Event\Builder;


use Imagine\Exception\RuntimeException;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use Symfony\Component\EventDispatcher\Event;

class FfmpegRuntimeException extends Event
{

    const NAME = 'thumbnail.builder.ffmpeg.runtimeexception';

    /**
     * @var \FFMpeg\Exception\RuntimeException
     */
    private $_exception;

    /**
     * @var IThumbnailTicket
     */
    private $ticket;

    /**
     * FfmpegRuntimeException constructor.
     * @param \Exception $exception
     * @param IThumbnailTicket $ticket
     */
    public function __construct(\Exception $exception, IThumbnailTicket $ticket)
    {
        $this->_exception = $exception;

        $this->ticket = $ticket;
    }

    /**
     * @return RuntimeException
     */
    public function getException()
    {
        return $this->_exception;
    }

    /**
     * @return IThumbnailTicket
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}