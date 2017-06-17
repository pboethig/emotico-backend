<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 02:16
 */

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\IMetadataGenerator;
use Mittax\MediaConverterBundle\Ticket\Builder\IMetadataTicketBuilder;
use Mittax\MediaConverterBundle\Ticket\TicketAbstract;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
class Ticket extends TicketAbstract
{
    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * @var IMetadataGenerator
     */
    protected $_generator;

    /**
     * Ticket constructor.
     * @param IMetadataTicketBuilder $ticketBuilder
     */
    public function __construct(IMetadataTicketBuilder $ticketBuilder)
    {
        parent::__construct($ticketBuilder);

        $this->_storageItem = $ticketBuilder->getStorageItem();
        
        $this->_generator = $ticketBuilder->getGenerator();
    }

    /**
     * @return StorageItem
     */
    public function getStorageItem()
    {
        return $this->_storageItem;
    }

    /**
     * @return IMetadataGenerator
     */
    public function getGenerator()
    {
        return $this->_generator;
    }
}