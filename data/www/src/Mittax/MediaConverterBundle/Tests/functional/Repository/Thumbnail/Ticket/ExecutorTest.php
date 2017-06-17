<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 18:38
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Ticket;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Builder;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Executor;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Consumer;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class ConsumerTest
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket
 */
class ExdcutorTest extends AbstractKernelTestCase
{
    /**
     * @var Consumer
     */
    private $_consumer;

    /**
     * @var Builder
     */
    private static $_jobTicketBuilder;

    /**
     * @var ITicket[]
     */
    private $_jobTickets;

    /**
     * @var StorageItem
     */
    private $_storageItem;

    public function setUp()
    {
        parent::setUp();

        $imageMetadata = Filesystem::getCachedAdapter('storage')->getMetadata($this->_testPathList[0]);

        $this->_storageItem = new StorageItem($imageMetadata);
    }

    public function testJobticketsAndProducer()
    {
        self::$_jobTicketBuilder = new Builder($this->_storageItem);

        /**
         * Check Ticketcreation
         */
        $jobTicket = self::$_jobTicketBuilder->getJobTicket();

        $this->assertNotEmpty($jobTicket);

        /**
         * Check consumer
         */
        $executor = new Executor($jobTicket);

        $this->assertInstanceOf(Executor::class, $executor);

        $executor->execute();
    }
}