<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ffmpeg\LowresTicket;

use FFMpeg\FFMpeg;
use FFMpeg\Media\Video;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Event\Builder\FfmpegRuntimeException;
use Mittax\MediaConverterBundle\Event\Converter\Ffmpeg\LowresCreated;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\Thumbnail\FineDataCreated;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Ticket\Executor\ThumbnailTicketExecutorAbstract;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use \Ffmpeg\Exception\RuntimeException;
use Symfony\Component\Process\Process;

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
     * @return bool
     */
    public function execute() : bool
    {
        $ticket = $this->_ticket;

        try
        {
            $this->_init($ticket);

            $this->load($ticket->getStorageItem());

            $ticket = $this->downSample($ticket);

            $ticket = $this->_createLowres($ticket);

            $this->_cleanUp($ticket);

            $this->_dispatchEvent($ticket);
        }
        catch (\Exception $e)
        {
            $event = new FfmpegRuntimeException($e->getPrevious(),$ticket);

            Dispatcher::getInstance()->dispatch(FfmpegRuntimeException::NAME, $event);
        }

        return true;
    }


    private function _init(IThumbnailTicket $ticket)
    {
        $this->_ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries' => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe'),
        ]);

        //change to root dir to match flysystem root
        chdir("/var/www/storage");

        $this->_tempFolderVideoPath = $ticket->getCurrentTempFilePath() . '.' . $ticket->getStorageItem()->getExtension();
    }
    /**
     * Loads image as stream from flysystem and creates an Imagine Data object
     *
     * @param StorageItem $storageItem
     * @return bool
     */
    public function load(StorageItem $storageItem) : bool
    {
        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return IThumbnailTicket
     */
    private function _createLowres(IThumbnailTicket $ticket)
    {
        $exportFolderFilePath = $this->getExportFolderFilePath($ticket);
        /**
         * Create MP4
         */
        $lowresFilePath = $exportFolderFilePath . '_lowres.mp4';

        $downSampledFilePath = $exportFolderFilePath .".downsampled.avi";

        $ffmpegQuery = 'ffmpeg -i "' . $downSampledFilePath.'" -vcodec libx264 -crf 25 -acodec aac -strict experimental -threads 0 -t 60 "' . $lowresFilePath . '"';

        $process = new Process($ffmpegQuery);

        $process->run();

        return $ticket;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return string
     */
    public function downSample(IThumbnailTicket $ticket) : IThumbnailTicket
    {
        $exportFolderFilePath = $this->getExportFolderFilePath($ticket);

        /**
         * Downsample VideoFiles
         */
        $downSampledFilePath = $exportFolderFilePath . '.downsampled.avi';

        $ffmegDownsampleQuery = 'ffmpeg -i "' . $ticket->getStorageItem()->getPath() . '" -acodec libmp3lame -ac 2 "' . $downSampledFilePath.'"';

        $process = new Process($ffmegDownsampleQuery);

        $process->run();

        return $ticket;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return string
     */
    public function getExportFolderFilePath(IThumbnailTicket $ticket)
    {
        $uuid = Upload::md5($ticket->getStorageItem()->getFilename());

        $fileName = $ticket->getStorageItem()->getFilename(). '_' . $ticket->getCurrentSize()->getWidth() . 'x' . $ticket->getCurrentSize()->getHeight();

        $exportFolderPath = "export/" . $uuid . "/" . $fileName;

        return $exportFolderPath;
    }

    /**
     * @param IThumbnailTicket $jobTicket
     */
    private function _dispatchEvent(IThumbnailTicket $jobTicket)
    {
        Dispatcher::getInstance()->dispatch(LowresCreated::NAME, new LowresCreated($jobTicket));
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _cleanUp(IThumbnailTicket $ticket ) : bool
    {
        @unlink($this->_tempFolderVideoPath);

        return true;
    }
}