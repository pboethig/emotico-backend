<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket;

use Imagine\Image\Point;
use Imagine\Imagick\Imagine;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Event\Builder\ImagineRuntimeException;
use Mittax\MediaConverterBundle\Event\Thumbnail\FineDataCreated;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Ticket\Executor\ThumbnailTicketExecutorAbstract;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use Symfony\Component\Process\Process;

/**
 * Class Producer
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket\Request
 */
class Executor extends ThumbnailTicketExecutorAbstract
{
    /**
     * @var \Imagine\Imagick\Imagine
     */
    private $_imagine;

    /**
     * @var \Imagine\Imagick\Image
     */
    private $_currentImage;

    /**
     * @return bool
     */
    public function execute() : bool
    {
        $ticket = $this->_ticket;

        try
        {
            $this->_init();

            $this->load($ticket->getStorageItem());

            $this->_storeInExportFolder($ticket);

            $this->_storeTIFFVersionInTargetAssetFolder($ticket);
            
            $this->_storeLowresPNGVersionInAssetFolder($ticket);

            $this->_dispatchEvent($ticket);
        }
        catch (\Exception $e)
        {
            if($e->getPrevious())
            {
                $event = new ImagineRuntimeException($e->getPrevious(),$ticket);
            }else
            {
                $event = new ImagineRuntimeException($e, $ticket);
            }

            Dispatcher::getInstance()->dispatch(ImagineRuntimeException::NAME, $event);
        }

        return true;
    }

    private function _init()
    {
        $this->_imagine = new Imagine();
    }

    /**
     * Loads image as stream from flysystem and creates an Imagine Data object
     *
     * @param StorageItem $storageItem
     * @return bool
     */
    public function load(StorageItem $storageItem) : bool
    {
        $stream = fopen(Filesystem::getStoragePath($storageItem), 'r');

        $this->_currentImage = $this->_imagine->read($stream);

        return true;
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
    protected function _storeInExportFolder(IThumbnailTicket $ticket) : bool
    {
        $methodName = "convert" . strtolower($ticket->getStorageItem()->getExtension());

        if(method_exists($this, $methodName))
        {
            $this->$methodName($ticket);

            return true;
        }

        $im = $this->_currentImage->getImagick();
        $im->setResolution(90, 90);
        $im->setImageFormat($ticket->getCurrentOutputFormat()->getFormat());
        $im->adaptiveResizeImage($ticket->getCurrentBox()->getWidth(), 0);
        $im->writeImage($this->_ticket->getCurrentTargetStoragePath());

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    public function convertpsd(IThumbnailTicket $ticket)
    {
        $targetFolder = Filesystem::createStorageFolder($ticket->getStorageItem(),'export');

        $targetFileName = $ticket->getStorageItem()->getFilename()
            . '_' . $ticket->getCurrentBox()->getWidth()
            . 'x' . $ticket->getCurrentBox()->getHeight()
            . '.' . $ticket->getCurrentOutputFormat()->getFormat();

        $targetPath = $targetFolder .'/' . $targetFileName;


        $im = $this->_currentImage->getImagick();

        //always use layer 0 in hope of transparency channel
        $im->setIteratorIndex(0);
        $im->setResolution(90, 90);

        $im->setImageFormat($ticket->getCurrentOutputFormat()->getFormat());
        $im->adaptiveResizeImage($ticket->getCurrentBox()->getWidth(), 0);
        $im->writeImage($targetPath);

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * use imagemagic convert to keep original size
     * @return bool
     */
    protected function _storeTIFFVersionInTargetAssetFolder(IThumbnailTicket $ticket ): bool
    {
        $targetFormat = 'tiff';

        if($ticket->getStorageItem()->getExtension() == $targetFormat)
        {
            return true;
        }

        $sourcePath = Filesystem::getStoragePath($this->_ticket->getStorageItem());

        $targetPath = Filesystem::getStoragePath($this->_ticket->getStorageItem(), $targetFormat);

        $command = 'convert "' . $sourcePath .'" "'. $targetPath.'"';

        $process = new Process($command);

        $process->run();

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * keep imagemick to autoreduce to webformats
     *
     * @return bool
     */
    protected function _storeLowresPNGVersionInAssetFolder(IThumbnailTicket $ticket ): bool
    {
        $targetFormat='png';

        $im = $this->_currentImage->getImagick();

        if($ticket->getStorageItem()->getExtension() == $targetFormat)
        {
            $im->clear();
            $im->destroy();

            return true;
        }

        $targetPath = Filesystem::getStoragePath($ticket->getStorageItem(), $targetFormat);

        $im->setImageCompression(0);
        $im->setImageCompressionQuality(100);

        $im->setImageFormat($targetFormat);
        $im->writeImage($targetPath);

        $im->clear();
        $im->destroy();

        return true;
    }

}