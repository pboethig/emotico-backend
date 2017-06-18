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
     * @var IAdapter
     */
    private $_cachedFilesystemAdapter;

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

            $this->_storeThumbnailInLocalTempFolder($ticket);

            $this->_storeInTargetStorageFolder($ticket);

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

        $this->_cachedFilesystemAdapter = Filesystem::getCachedAdapter('storage');
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
     * Stores thumbnail in local OS tempfolder
     *
     * @return bool
     */
    private function _storeThumbnailInLocalTempFolder(IThumbnailTicket $ticket ) : bool
    {
        $methodName = "convert" . strtolower($ticket->getStorageItem()->getExtension());

        if(method_exists($this, $methodName))
        {
            $this->$methodName($ticket);

            return true;
        }

        $im = $this->_currentImage->getImagick();
        $im->setResolution(72, 72);
        $im->setImageFormat($ticket->getCurrentOutputFormat()->getFormat());
        $im->adaptiveResizeImage($ticket->getCurrentBox()->getWidth(), 0);
        $im->writeImage('storage/' . $ticket->getCurrentTempFilePath());

        $im->clear();
        $im->destroy();

        /**
        $this->_currentImage->crop(new Point(0, 0), $ticket->getCurrentBox())
            ->save('storage/' . $ticket->getCurrentTempFilePath(), $ticket->getCurrentOutputFormat()->getQuality());
        */
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
    protected function _storeInTargetStorageFolder(IThumbnailTicket $ticket ): bool
    {
        Filesystem::getCachedAdapter('storage')->copy($ticket->getCurrentTempFilePath(),$ticket->getCurrentTargetStoragePath());

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _cleanUp(IThumbnailTicket $ticket ) : bool
    {
        Filesystem::getCachedAdapter('storage')->delete($ticket->getCurrentTempFilePath());

        return true;
    }
}