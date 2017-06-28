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
use Mittax\MediaConverterBundle\Service\Converter\Cropping\Scaling;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Ticket\Executor\IExecutor;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;
use Symfony\Component\Process\Process;

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
            $this->cropToAssetFolder();

            $this->_dispatchEvent();
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

    private function _dispatchEvent()
    {
        Dispatcher::getInstance()->dispatch(HiresCroppingCreated::NAME, new HiresCroppingCreated($this->_ticket));
    }

    /**
     * @return bool
     */
    protected function cropToAssetFolder(): bool
    {
        $sourceImage = Config::getStoragePath().'/'.$this->_ticket->getStorageItem()->getPath();

        $targetFolder = Config::getStoragePath() . '/assets/' . $this->_ticket->getJobId();

        @mkdir($targetFolder, 0777);

        $fileName = $this->_ticket->getStorageItem()->getBasename().'_'.$this->_ticket->getCroppingData()->getHash().'_crop.tiff';

        $targetPath = $targetFolder . '/' . $fileName;

        $storagePath = Config::getStoragePath().'/'. $this->_ticket->getStorageItem()->getPath();

        $scaledCroppingData = Scaling::getHiresCroppingData($this->_ticket->getCroppingData(), $this->_ticket->getBrowserImageData(), $storagePath);

        $command = 'convert "'. $sourceImage
            . '" -crop '
            . $scaledCroppingData->getWidth()
            . 'x' . $scaledCroppingData->getHeight()
            . '+' . $scaledCroppingData->getTop()
            . '+' . $scaledCroppingData->getLeft()
            . ' "' . $targetPath . '"';


        $process = new Process($command);

        $process->run();

        return true;
    }
}