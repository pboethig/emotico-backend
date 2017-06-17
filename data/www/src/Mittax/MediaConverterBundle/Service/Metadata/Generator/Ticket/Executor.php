<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 11:02
 */

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket;

use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Event\Builder\ImagineRuntimeException;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Factory;
use Mittax\MediaConverterBundle\Ticket\Executor\MetadataExecutorAbstract;
use \Imagine\Exception\RuntimeException;
/**
 * Class Producer
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket\Request
 */
class Executor extends MetadataExecutorAbstract
{
    /**
     * @return bool
     */
    public function execute() : bool
    {
        $ticket = $this->_ticket;

        try
        {
            $ticket->getGenerator()->storeMetadataAsJsonFile();
        }
        catch (RuntimeException $e)
        {
            $event = new ImagineRuntimeException($e->getPrevious(),$ticket);

            Dispatcher::getInstance()->dispatch(ImagineRuntimeException::NAME, $event);
        }

        return true;
    }
}