<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\InDesignServer;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\InDesignServer\InDesignServerError;
use Mittax\MediaConverterBundle\Event\InDesignServer\SystemNotReachable;
use Mittax\MediaConverterBundle\Event\Listener\Messages\SystemError;
use Mittax\MediaConverterBundle\Event\Thumbnail\CollectionCreated;
use Mittax\MediaConverterBundle\Event\Thumbnail\FineDataCreated;
use Mittax\MediaConverterBundle\Exception\InDesignServerNotAvailableException;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Builder;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class SystemNotReachableTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $event = new SystemNotReachable(new InDesignServerNotAvailableException('INDD Server under IP : xxxxx not available'));

        $_event = Dispatcher::getInstance()->dispatch(SystemNotReachable::NAME, $event);

        $this->assertNotNull($_event);

        $this->assertEquals(InDesignServerNotAvailableException::class, get_class($event->getException()));
    }
}