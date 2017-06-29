<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\ThumbnailTicket;

use FFMpeg\FFMpeg;
use FFMpeg\Media\Video;
use Mittax\MediaConverterBundle\Event\Builder\FfmpegRuntimeException;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\Thumbnail\FineDataCreated;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket\Producer;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Ticket\Executor\ThumbnailTicketExecutorAbstract;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;

/**
 * Class Producer
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket\Request
 */
class Executor extends ThumbnailTicketExecutorAbstract
{
    /**
     * @var FFMpeg
     */
    private $_ffmpeg;

    /**
     * @var Video
     */
    private $_video;

    /**
     * @return bool
     */
    public function execute() : bool
    {
        $ticket = $this->_ticket;

        try
        {
            $this->_init();

            $this->load();

            $this->_createThumbnail();

            $this->_createLowresTicket();

            $this->_dispatchEvent($ticket);

        }
        catch (\Exception $e)
        {
            if($e->getPrevious())
            {
                $event = new FfmpegRuntimeException($e->getPrevious(), $ticket);
            }
            else
            {
                $event = new FfmpegRuntimeException($e, $ticket);
            }

            Dispatcher::getInstance()->dispatch(FfmpegRuntimeException::NAME, $event);
        }

        return true;
    }

    private function _init()
    {
        $this->_ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries' => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe'),
        ]);
    }

    /**
     * Loads image as stream from flysystem and creates an Imagine Data object
     *
     * @return bool
     */
    public function load() : bool
    {
        $this->_video = $this->_ffmpeg->open(Filesystem::getStoragePath($this->_ticket->getStorageItem()));

        return true;
    }

    /**
     * Create thumbnail
     */
    private function _createThumbnail()
    {
        $filePath = $this->_ticket->getCurrentTargetStoragePath();

            $this->_video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))
            ->save($filePath);
    }

    /**
     * create lowresticket
     */
    private function _createLowresTicket()
    {
        $producer = new Producer([$this->_ticket]);

        $producer->execute();
    }

    /**
     * @param IThumbnailTicket $jobTicket
     */
    private function _dispatchEvent(IThumbnailTicket $jobTicket)
    {
        Dispatcher::getInstance()->dispatch(FineDataCreated::NAME, new FineDataCreated($jobTicket));
    }
}