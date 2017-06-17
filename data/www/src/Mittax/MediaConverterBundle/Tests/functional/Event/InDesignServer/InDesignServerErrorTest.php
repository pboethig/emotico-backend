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
use Mittax\MediaConverterBundle\Event\Thumbnail\CollectionCreated;
use Mittax\MediaConverterBundle\Event\Thumbnail\FineDataCreated;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Builder;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class InDesignServerErrorTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {

        $mockIndesignServerResponse = $this->getIndesignServerExportJPGMockResponse();

        $inDesignEventService = new \Mittax\MediaConverterBundle\Service\InDesignServer\Event($this->container);

        $response = $inDesignEventService->buildTypedResponseFromJson(json_encode($mockIndesignServerResponse));

        $response->clientEvent = 'indesignserver.error';

        $response->errors = ['error1', 'error2'];

        $event = new InDesignServerError($response);

        $event = Dispatcher::getInstance()->dispatch(InDesignServerError::NAME, $event);

        $this->assertNotNull($event);

    }

    
}