<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket;

use Imagine\Image\Point;
use Imagine\Imagick\Imagine;
use Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingCreated;
use Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingException;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Ticket\Executor\IExecutor;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;

/**
 * Class Executor
 * @package Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket
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
        try
        {
            $this->_init();

            $this->loadTiffVersion();

            $this->_storeInAssetFolder();

            $this->_dispatchEvent();

            $this->_cleanUp();
        }
        catch (\Exception $e)
        {
            if($e->getPrevious())
            {
                $event = new HiresCroppingException($e->getPrevious(),$this->_ticket);
            }
            else
            {
                $event = new HiresCroppingException($e, $this->_ticket);
            }

            Dispatcher::getInstance()->dispatch(HiresCroppingException::NAME, $event);
        }

        return true;
    }

    private function _init()
    {
        $this->_imagine = new Imagine();
    }

    /**
     * @return bool
     */
    public function loadTiffVersion() : bool
    {
        $stream = fopen(Config::getStoragePath().'/'. $this->_ticket->getStorageItem()->getPath(), 'r');

        $this->_currentImage = $this->_imagine->read($stream);

        return true;
    }


    private function _dispatchEvent()
    {
        Dispatcher::getInstance()->dispatch(HiresCroppingCreated::NAME, new HiresCroppingCreated($this->_ticket));
    }

    /**
     * @return bool
     */
    protected function _storeInAssetFolder(): bool
    {
        $targetFolder = Config::getStoragePath() . '/assets/' . $this->_ticket->getJobId();

        @mkdir($targetFolder, 0777);

        $fileName = $this->_ticket->getStorageItem()->getBasename().'_'.$this->_ticket->getCroppingData()->getHash().'_crop.tiff';

        $targetPath = $targetFolder . '/' . $fileName;

        $im = $this->_currentImage->getImagick();

        $im->setImageFormat('tiff');

        $im->cropImage($this->_ticket->getCroppingData()->getWidth(),$this->_ticket->getCroppingData()->getHeight(), $this->_ticket->getCroppingData()->getTop(), $this->_ticket->getCroppingData()->getLeft());
        $im->writeImage($targetPath);

        $im->clear();
        $im->destroy();

        return true;
    }

    /**
     * @return bool
     */
    protected function _cleanUp() : bool
    {
        return true;
    }
}