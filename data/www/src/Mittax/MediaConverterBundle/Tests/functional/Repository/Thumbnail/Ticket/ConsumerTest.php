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
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Consumer;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class ConsumerTest
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ThumbnailTicket
 */
class ConsumerTest extends AbstractKernelTestCase
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

    public function testBuilder()
    {
        self::$_jobTicketBuilder = new Builder($this->_storageItem);

        $this->assertInstanceOf(Builder::class, self::$_jobTicketBuilder);
    }

    public function testJobticketsAndConsumer()
    {
        /**
         * Check Ticketcreation
         */
        $this->_jobTickets[] = self::$_jobTicketBuilder->getJobTicket();

        $this->assertNotEmpty($this->_jobTickets);

        /**
         * Check consumer
         */
        $this->_consumer = new Consumer($this->_jobTickets);

        $this->assertInstanceOf(Consumer::class, $this->_consumer);

        /**
         * Check returncallback
         */
        $returnCallBack = $this->_consumer->getReturnCallback();

        $this->assertTrue(is_callable($returnCallBack));

        /**
         * Test getting jobticket
         */
        $jobTicket = self::$_jobTicketBuilder->getJobTicket();

        $this->assertNotEmpty($jobTicket->getJobId());

        /**
         * Test real message with ticket on real storageitem
         */
        $message = new AMQPMessage($jobTicket->serialize());

        $returnedTicket = $returnCallBack('replyCode', 'replyText', 'exchange', 'routingKey', $message);

        $this->_consumer->execute();

        $this->assertFalse($returnedTicket);
    }
}