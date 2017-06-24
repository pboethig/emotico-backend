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
use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Ticket\Executor\ThumbnailTicketExecutorAbstract;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;

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

            $this->_storeJPGVersionInTargetStorageFolder($ticket);

            $this->_storeTIFFVersionInTargetStorageFolder($ticket);
            
            $this->_storePNGVersionInTargetStorageFolder($ticket);

            $this->_dispatchEvent($ticket);

            $this->_cleanUp($ticket);
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

        //change to root dir to match flysystem root
        chdir(__DIR__ . '/../../../../../../../..');
    }

    /**
     * Loads image as stream from flysystem and creates an Imagine Data object
     *
     * @param StorageItem $storageItem
     * @return bool
     */
    public function load(StorageItem $storageItem) : bool
    {
        $stream = fopen('/var/www/storage/'.$storageItem->getPath(), 'r');

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

        $targetFolder = Upload::md5($ticket->getStorageItem()->getFilename());

        @mkdir(Config::getExportPath().'/'.$targetFolder);

        $targetFileName = $ticket->getStorageItem()->getFilename()
            . '_' . $ticket->getCurrentBox()->getWidth()
            . 'x' . $ticket->getCurrentBox()->getHeight()
            . '.' . $ticket->getCurrentOutputFormat()->getFormat();

        $targetPath = 'storage/export/' . $targetFolder .'/' . $targetFileName;


        $im = $this->_currentImage->getImagick();
        $im->setResolution(90, 90);
        $im->setImageFormat($ticket->getCurrentOutputFormat()->getFormat());
        $im->adaptiveResizeImage($ticket->getCurrentBox()->getWidth(), 0);
        $im->writeImage($targetPath);

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _storeJPGVersionInTargetStorageFolder(IThumbnailTicket $ticket ): bool
    {
        $im = $this->_currentImage->getImagick();

        if($ticket->getStorageItem()->getExtension()=='jpg')
        {
            return true;
        }

        $targetPath = 'storage/assets/' . Upload::md5($ticket->getStorageItem()->getFilename()) . '/' . $ticket->getStorageItem()->getBasename().'.jpg';

        $im->setImageCompression(0);
        $im->setImageCompressionQuality(100);
        $im->setImageFormat('jpg');
        $im->writeImage($targetPath);

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _storeTIFFVersionInTargetStorageFolder(IThumbnailTicket $ticket ): bool
    {
        $im = $this->_currentImage->getImagick();

        if($ticket->getStorageItem()->getExtension()=='tiff')
        {
            return true;
        }

        $targetPath = 'storage/assets/' . Upload::md5($ticket->getStorageItem()->getFilename()) . '/' . $ticket->getStorageItem()->getBasename().'.tiff';

        $im->setImageCompression(0);
        $im->setImageCompressionQuality(100);
        $im->setImageFormat('tiff');
        $im->writeImage($targetPath);

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _storePNGVersionInTargetStorageFolder(IThumbnailTicket $ticket ): bool
    {
        $im = $this->_currentImage->getImagick();

        if($ticket->getStorageItem()->getExtension()=='png')
        {
            $im->clear();
            $im->destroy();

            return true;
        }

        $targetPath = 'storage/assets/' . Upload::md5($ticket->getStorageItem()->getFilename()) . '/' . $ticket->getStorageItem()->getBasename().'.png';

        $im->setImageCompression(0);
        $im->setImageCompressionQuality(100);

        $im->setImageFormat('png');
        $im->writeImage($targetPath);

        $im->clear();
        $im->destroy();

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _cleanUp(IThumbnailTicket $ticket ) : bool
    {
        return true;
    }
}