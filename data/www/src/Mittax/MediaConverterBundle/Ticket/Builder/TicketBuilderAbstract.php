<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.01.17
 * Time: 14:15
 */

namespace Mittax\MediaConverterBundle\Ticket\Builder;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Ticket;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;

abstract class TicketBuilderAbstract implements ITicketBuilder
{
    /**
     * @var ITicket
     */
    protected $_jobTicket;

    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * @param StorageItem $storageItem
     * @return bool
     */
    abstract function init(StorageItem $storageItem) : bool;

    /**
     * @return bool
     */
    abstract function build() : bool;

    /**
     * Builder constructor.
     * @param StorageItem $storageItem
     */
    public function __construct(StorageItem $storageItem)
    {
        $this->_storageItem = $storageItem;

        $this->init($this->_storageItem);
    }

    /**
     * @return Ticket
     */
    public function getJobTicket() : Ticket
    {
        return $this->_jobTicket;
    }

    public function getStorageItem()
    {
        return $this->_storageItem;
    }
}