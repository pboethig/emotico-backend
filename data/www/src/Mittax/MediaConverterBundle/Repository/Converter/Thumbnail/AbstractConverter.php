<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 19:46
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\ValueObjects\ConverterConfig;

/**
 * Class ThumbnailAbstract
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
abstract class AbstractConverter implements IConverter
{
    /**
     * @var ITicketBuilder[]
     */
    protected $_jobTickets = [];

    /**
     * @var ConverterConfig
     */
    protected $_converterConfig;

    /**
     * @var string
     */
    protected $_uuid;

    /**
     * @param StorageItem $thumbail
     * @return ITicketBuilder
     */
    abstract public function createThumbnails(StorageItem $thumbail) : ITicketBuilder;

    /**
     * ThumbnailAbstractConverter constructor.
     * @param ConverterConfig $converterConfig
     *
     */
    public function __construct(ConverterConfig $converterConfig)
    {
        $this->_converterConfig = $converterConfig;
    }

    public function getName() : string
    {
        return $this->_converterConfig->getName();
    }

    /**
     * @return ConverterConfig
     */
    public function getConverterConfig() : ConverterConfig
    {
        return $this->_converterConfig;
    }

    public function getUuid() : string
    {
        return $this->_converterConfig->getVersion()->getVersionString() . $this->_converterConfig->getName();
    }

    /**
     * @return \Mittax\MediaConverterBundle\Ticket\ITicketBuilder[]
     */
    public function getJobTickets() : Array
    {
        return $this->_jobTickets;
    }

    /**
     * @param ITicket $ticket
     */
    public function attachJobTicket(ITicket $ticket)
    {
        $this->_jobTickets[$ticket->getJobId()] = $ticket;
    }
}