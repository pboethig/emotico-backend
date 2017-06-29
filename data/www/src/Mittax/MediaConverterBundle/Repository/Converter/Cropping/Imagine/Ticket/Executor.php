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
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
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
        $sourceImagePath = Filesystem::getStoragePath($this->_ticket->getStorageItem(),'tiff');

        $targetFolder = Filesystem::createStorageFolder($this->_ticket->getStorageItem());

        $fileName = Filesystem::getHiresCroppingFilename($this->_ticket->getStorageItem(), $this->_ticket->getCroppingData());

        $targetPath = $targetFolder . '/' . $fileName;

        $scaledCroppingData = Scaling::getHiresCroppingData($this->_ticket->getCroppingData(), $this->_ticket->getBrowserImageData(), $sourceImagePath);

        $command = 'convert "'. $sourceImagePath
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