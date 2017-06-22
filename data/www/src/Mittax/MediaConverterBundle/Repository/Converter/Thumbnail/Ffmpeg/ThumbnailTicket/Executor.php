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
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
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
     * @var string
     */
    private $_currentVideoContent;

    /**
     * @var string
     */
    private $_tempFolderVideoPath;

    /**
     * @var string
     */
    private $_tempThumbnailPath;

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
            $this->_init($ticket);

            $this->load($ticket->getStorageItem());

            $this->_storeOriginalVideoInTempFolder($ticket);

            $this->_createThumbnailInTempFolder($ticket);

            $this->_storeThumbnailInTargetFolder($ticket);

            $this->_createLowresTicket($ticket);

            $this->_cleanUp($ticket);

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

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _storeThumbnailInTargetFolder(IThumbnailTicket $ticket ): bool
    {
        @unlink($ticket->getCurrentTargetStoragePath());

        $content = file_get_contents('storage/' . $this->_tempThumbnailPath);

        Filesystem::getCachedAdapter('storage')->write($ticket->getCurrentTargetStoragePath(), $content, new \League\Flysystem\Config());

        return true;
    }

    private function _init(IThumbnailTicket $ticket)
    {
        $this->_ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries' => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe'),
        ]);
    
        //change to root dir to match flysystem root
        chdir(__DIR__ . '/../../../../../../../..');

        $this->_tempFolderVideoPath = $ticket->getCurrentTempFilePath() . '.' . $ticket->getStorageItem()->getExtension();

        $this->_tempThumbnailPath = $this->_tempFolderVideoPath .'.' . $ticket->getCurrentOutputFormat()->getFormat();
    }
    /**
     * Loads image as stream from flysystem and creates an Imagine Data object
     *
     * @param StorageItem $storageItem
     * @return bool
     */
    public function load(StorageItem $storageItem) : bool
    {
        $this->_currentVideoContent = Filesystem::getCachedAdapter('storage')->read($storageItem->getPath());

        return true;
    }

    /**
     * Stores thumbnail in local OS tempfolder
     *
     * @return bool
     */
    private function _storeOriginalVideoInTempFolder(IThumbnailTicket $ticket ) : bool
    {
        Filesystem::getCachedAdapter('storage')->copy($ticket->getStorageItem()->getPath(), $this->_tempFolderVideoPath);

        return true;
    }

    private function _createThumbnailInTempFolder(IThumbnailTicket $ticket)
    {
        $this->_video = $this->_ffmpeg->open('storage/' . $this->_tempFolderVideoPath);

        $this->_video
            ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))
            ->save('storage/' . $this->_tempThumbnailPath);
    }

    /**
     * @param IThumbnailTicket $ticket
     */
    private function _createLowresTicket(IThumbnailTicket $ticket)
    {
        $producer = new Producer([$ticket]);

        $producer->execute();
    }

    /**
     * @param IThumbnailTicket $jobTicket
     */
    private function _dispatchEvent(IThumbnailTicket $jobTicket)
    {
        Dispatcher::getInstance()->dispatch(FineDataCreated::NAME, new FineDataCreated($jobTicket));
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _cleanUp(IThumbnailTicket $ticket ) : bool
    {
        Filesystem::getCachedAdapter('storage')->delete($this->_tempThumbnailPath);

        Filesystem::getCachedAdapter('storage')->delete($this->_tempFolderVideoPath);

        return true;
    }
}