<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\Converter;

use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Event\Converter\Ffmpeg\LowresCreated;

use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class LowresCreatedTest
 * @package Mittax\MediaConverterBundle\Tests\Event\Converter
 */
class LowresCreatedTest extends AbstractKernelTestCase
{

    private $_uuid;

    /**
     * @var \Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ItemRepository
     */
    private $_repository;

    public function setUp()
    {
        parent::setUp();

        $this->_storageItem = new StorageItem($this->_flySystemItemMock);

        $this->buildConverterFactory();

        $this->_uuid = $this->_thumbnailConverterFactory->getRepositoryConfiguration()->getUuid();

        $this->_repository = $this->_thumbnailConverterFactory->getByUuid($this->_uuid);
    }

    public function testInstance()
    {
        $storageRepository = $this->_storageRepositoryFactory->getByUuid($this->_storageRepositoryConfig->getUuid());

        $storageCollection = $storageRepository->getCollection();

        $thumbnailData = $this->_repository->createThumbnails($storageCollection);

        //dd($thumbnailData['jobTickets']);

        $jobTickets = $thumbnailData['jobTickets'];

        foreach ($jobTickets as $jobId=>$ticket)
        {
            foreach ($ticket as $_ticket)
            {

            }
        }


        $event = new LowresCreated($_ticket);

        $this->assertInstanceOf(IThumbnailTicket::class, $event->getJobTicket());

        $event = Dispatcher::getInstance()->dispatch(\Mittax\MediaConverterBundle\Event\Converter\Ffmpeg\LowresCreated::NAME, $event);

        $this->assertInstanceOf(\Mittax\MediaConverterBundle\Event\Converter\Ffmpeg\LowresCreated::class, $event);
    }
}