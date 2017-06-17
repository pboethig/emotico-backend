<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.01.17
 * Time: 14:46
 */

namespace Mittax\MediaConverterBundle\Tests\Ticket\Metadata;

use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Builder;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Producer;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Ticket\Ticket;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class ProducerTest
 * @package Mittax\MediaConverterBundle\Tests\Ticket\Metadata
 */
class ProducerTest extends AbstractKernelTestCase
{
    /**
     * @var \Mittax\MediaConverterBundle\Collection\StorageItem
     */
    private $_jpgCollection;

    /**
     * @var StorageItem
     */
    private $_storageCollection;

    
    public function setUp()
    {
        parent::setUp();

        $storageRepository = $this->_storageRepositoryFactory->getByUuid($this->_storageRepositoryConfig->getUuid());

        $this->_storageCollection = $storageRepository->getCollection();

        $this->_jpgCollection = $this->_storageCollection->filterByPropertyNameAndValue('extension', 'jpg');
    }

    public function testInstance()
    {
        $generator = new \Mittax\MediaConverterBundle\Service\Metadata\Generator\Exif($this->_jpgCollection->getFirstItem());

        $builder = new Builder($this->_jpgCollection->getFirstItem(), $generator);

        $ticket = new Ticket($builder);
        $rabbitMQPProducer = new Producer([$ticket]);

        $rabbitMQPProducer->execute();

        $this->assertNotNull($rabbitMQPProducer);
    }
}