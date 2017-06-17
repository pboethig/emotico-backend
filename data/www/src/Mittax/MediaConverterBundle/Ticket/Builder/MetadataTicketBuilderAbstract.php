<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.01.17
 * Time: 14:17
 */

namespace Mittax\MediaConverterBundle\Ticket\Builder;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\IMetadataGenerator;

abstract class MetadataTicketBuilderAbstract extends TicketBuilderAbstract implements IMetadataTicketBuilder
{
    /**
     * @var IMetadataGenerator
     */
    protected $_generator;

    /**
     * MetadataTicketBuilderAbstract constructor.
     * @param StorageItem $storageItem
     * @param IMetadataGenerator $generator
     */
    public function __construct(StorageItem $storageItem, IMetadataGenerator $generator)
    {
        $this->_generator = $generator;

        parent::__construct($storageItem);
    }

    /**
     * @return IMetadataGenerator
     */
    public function getGenerator() : IMetadataGenerator
    {
        return $this->_generator;
    }
}