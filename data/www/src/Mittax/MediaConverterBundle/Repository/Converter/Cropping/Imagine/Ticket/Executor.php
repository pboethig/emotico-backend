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
use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Ticket\Executor\IExecutor;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;

/**
 * Class Executor
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket
 */
class Executor implements IExecutor
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
     * @var ICroppingTicket
     */
    private $_ticket;
    /**
     * Executor constructor.
     * @param ICroppingTicket $ticket
     */
    public function __construct(ICroppingTicket $ticket)
    {
        $this->_ticket = $ticket;
    }
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

            $this->_storeAssetFolder($ticket);

            $this->_dispatchEvent($ticket);

            $this->_cleanUp($ticket);
        }
        catch (\Exception $e)
        {
            if($e->getPrevious())
            {
                $event = new ImagineRuntimeException($e->getPrevious(),$ticket);
            }
            else
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
        chdir(__DIR__ . '/../../../../../../../data');
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
     * @param ICroppingTicket $jobTicket
     */
    private function _dispatchEvent(ICroppingTicket $jobTicket)
    {
        Dispatcher::getInstance()->dispatch(FineDataCreated::NAME, new FineDataCreated($jobTicket));
    }

    /**
     * @param ICroppingTicket $ticket
     * @return bool
     */
    protected function _storeAssetFolder(ICroppingTicket $ticket ): bool
    {
        $im = $this->_currentImage->getImagick();

        $targetPath = 'storage/assets/' . Upload::md5($ticket->getStorageItem()->getFilename()) . '/' . $ticket->getStorageItem()->getBasename().'_crop.tiff';

        $im->setImageCompression(0);
        $im->setImageCompressionQuality(100);
        $im->setImageFormat('tiff');
        $im->writeImage($targetPath);

        $im->clear();
        $im->destroy();

        return true;
    }

    /**
     * @param ICroppingTicket $ticket
     * @return bool
     */
    protected function _cleanUp(ICroppingTicket $ticket ) : bool
    {
        return true;
    }
}