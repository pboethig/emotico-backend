<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket;

use Imagine\Image\Point;
use Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingCreated;
use Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingException;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Service\Converter\Cropping\Crop;
use Mittax\MediaConverterBundle\Ticket\Executor\IExecutor;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;

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

            $this->dispatchEvent();
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

    /**
     * Dispatches final event
     */
    private function dispatchEvent()
    {
        Dispatcher::getInstance()->dispatch(HiresCroppingCreated::NAME, new HiresCroppingCreated($this->_ticket));
    }

    /**
     * @return bool
     */
    protected function cropToAssetFolder(): bool
    {
        $croppingService = new Crop();

        $command = $croppingService->crop($this->_ticket->getSourceImagePath(), $this->_ticket->getTargetPath(), $this->_ticket->getCroppingData(), $this->_ticket->getBrowserImageData());

        return true;
    }
}