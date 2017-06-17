<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.01.17
 * Time: 10:29
 */

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator;


use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Builder;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Producer;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Ticket;

/**
 * Class Factory
 * @package Mittax\MediaConverterBundle\Service\Metadata\Generator
 */
class Factory
{
    /**
     * @var IMetadataGenerator[]
     */
    private $_generators = ['Mittax\MediaConverterBundle\Service\Metadata\Generator\Exif'];

    /**
     * @var StorageItem
     */
    private $_collection;

    /**
     * Factory constructor.
     * @param \Mittax\ObjectCollection\CollectionAbstract $storageItemCollection
     */
    public function __construct(\Mittax\ObjectCollection\CollectionAbstract  $storageItemCollection)
    {
        $this->_collection = $storageItemCollection;
    }

    /**
     * @return array
     */
    public function read() : Array
    {
        $paths = [];

        foreach ($this->_collection->getAllItems() as $storageItem)
        {
            foreach ($this->_generators as $generatorClassName)
            {
                /** @var  $generator IMetadataGenerator*/
                $generator = new $generatorClassName($storageItem);

                if ($generator->doesSupportsFormat())
                {
                    $paths[$storageItem->getUuid()] = $generator->read();
                }
            }
        }

        return $paths;
    }

    /**
     * @return array
     */
    public function triggerCreation() : Array
    {
        $paths = [];

        $tickets = [];

        foreach ($this->_collection->getAllItems() as $storageItem)
        {
            foreach ($this->_generators as $generatorClassName)
            {
                /** @var  $generator IMetadataGenerator*/
                $generator = new $generatorClassName($storageItem);

                if ($generator->doesSupportsFormat())
                {
                    $tickets[] = new Ticket(new Builder($storageItem, $generator));
                }
            }
        }

        $rabbitMQRequest = new Producer($tickets);

        $rabbitMQRequest->execute();

        return $paths;
    }
}