<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 11:05
 */

namespace Mittax\MediaConverterBundle\Tests\Ticket\Converter\ThumbnailTicket;

use Mittax\MediaConverterBundle\Collection\Thumbnail;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ItemRepository;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;

/**
 * Class JobstTest
 * @package Mittax\MediaConverterBundle\Tests\ThumbnailTicket
 */
class ThumbailTest extends AbstractKernelTestCase
{
    private $_uuid;

    /**
     * @var ItemRepository
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

    public function testCreateThumbnailsFromCollection()
    {

        $storageRepository = $this->_storageRepositoryFactory->getByUuid($this->_storageRepositoryConfig->getUuid());

        $storageCollection = $storageRepository->getCollection();

        /**
         * Expensive test is configurable
         */
        if(Config::getMediaConverterConfig()['functionaltests']['executeThumbnailGeneration'])
        {
            $thumbnailData = $this->_repository->createThumbnails($storageCollection);

            /** @var  $thumbnailCollection Thumbnail*/
            $thumbnailCollection = $thumbnailData['collection'];

            $this->assertInstanceOf(Thumbnail::class, $thumbnailCollection);

            $this->assertNotEmpty($thumbnailData['jobTickets']);

            /** @var  $jobtickets ITicket[]*/
            $jobtickets = $thumbnailData['jobTickets'];

            foreach ($jobtickets as $ticketList)
            {
                foreach($ticketList as $ticketId => $ticket)
                {
                    $serializedTicket = $ticket->serialize();

                    $this->assertNotEmpty($serializedTicket);
                }
            }
        }
    }

    public function testProduce()
    {
        //$probe = parent::startBlackFire(4);
        $pathPathList = $this->_testPathList;

        $storageRepositoryConfig = new StorageRepositoryConfig($pathPathList);

        $storageRepository = $this->_storageRepositoryFactory->getByUuid($storageRepositoryConfig->getUuid());

        $storageCollection = $storageRepository->getCollection();

        $thumbnailData = $this->_repository->createThumbnails($storageCollection);

        $this->assertNotNull($thumbnailData);

        $this->_repository->executeJobTickets($thumbnailData['jobTickets']);
        //parent::stopBlackFire($probe);
    }
}