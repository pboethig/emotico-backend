<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 08.01.17
 * Time: 12:53
 */

namespace Mittax\MediaConverterBundle\Ticket\Executor;


use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;

abstract class ThumbnailTicketExecutorAbstract implements IExecutor
{

    /**
     * @var IThumbnailTicket
     */
    protected $_ticket;

    /**
     * Executor constructor.
     * @param IThumbnailTicket $ticket
     */
    public function __construct(IThumbnailTicket $ticket)
    {
        $this->_ticket = $ticket;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _storeInTargetStorageFolder(IThumbnailTicket $ticket ): bool
    {
        @unlink($ticket->getCurrentTargetStoragePath());

        $content = file_get_contents($ticket->getCurrentTempFilePath());

        Filesystem::getCachedAdapter('storage')->write($ticket->getCurrentTargetStoragePath(), $content, new \League\Flysystem\Config());

        return true;
    }

    /**
     * @param IThumbnailTicket $ticket
     * @return bool
     */
    protected function _cleanUp(IThumbnailTicket $ticket ) : bool
    {
        @unlink($ticket->getCurrentTempFilePath());

        //otherwise you get out of ram after 1000 conkruent messages
        gc_collect_cycles();

        return true;
    }

}