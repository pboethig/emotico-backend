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

class ImagineRuntimeException extends Event
{

    const NAME = 'thumbnail.builder.imagine.runtimeexception';

    /**
     * @var \Imagine\Exception\RuntimeException
     */
    private $_exception;

    /**
     * @var IThumbnailTicket
     */
    private $_ticket;

    /**
     * ImagineRuntimeException constructor.
     * @param \Exception $exception
     * @param IThumbnailTicket $thumbnailTicket
     */
    public function __construct(\Exception $exception, IThumbnailTicket $thumbnailTicket)
    {
        $this->_exception = $exception;

        $this->_ticket = $thumbnailTicket;
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
        return $this->_ticket;
    }
}