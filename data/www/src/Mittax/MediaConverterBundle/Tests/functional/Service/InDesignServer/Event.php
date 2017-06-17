<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.01.17
 * Time: 09:59
 */
namespace Mittax\MediaConverterBundle\Tests\Service\InDesignServer;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class ExifTest
 * @package Mittax\MediaConverterBundle\Tests\Service\Metaata
 */
class Event extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testIndesignServerEvent()
    {
        $inDesignEventService = new \Mittax\MediaConverterBundle\Service\InDesignServer\Event($this->container);

        $mockIndesignServerResponse = $this->getIndesignServerExportJPGMockResponse();

        $response = $inDesignEventService->buildTypedResponseFromJson(json_encode($mockIndesignServerResponse));

        $inDesignEventService->dispatch($response);
    }



    public function testBuildTypedResponseFromJson()
    {
        $mockIndesignServerResponse = $this->getIndesignServerExportJPGMockResponse();

        $inDesignEventService = new \Mittax\MediaConverterBundle\Service\InDesignServer\Event($this->container);

        $response = $inDesignEventService->buildTypedResponseFromJson(json_encode($mockIndesignServerResponse));

        $this->assertEquals($response->ticketId, $mockIndesignServerResponse->ticketId);

        $this->assertEquals($response->urls, $mockIndesignServerResponse->urls);

        $this->assertEquals($response->status, $mockIndesignServerResponse->status);
    }
}