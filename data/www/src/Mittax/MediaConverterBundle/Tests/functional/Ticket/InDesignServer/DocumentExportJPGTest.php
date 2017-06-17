<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.01.17
 * Time: 14:46
 */

namespace Mittax\MediaConverterBundle\Tests\Ticket\Metadata;
use JMS\Serializer\SerializationContext;
use JMS\SerializerBundle\JMSSerializerBundle;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use \Mittax\MediaConverterBundle\Ticket\InDesignServer\Commands\DocumentExportJPG;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\AdditionalData;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;
use \Mittax\MediaConverterBundle\Ticket\InDesignServer\Ticket;
/**
 * Class ProducerTest
 * @package Mittax\MediaConverterBundle\Tests\Ticket\Metadata
 */
class DocumentExportJPGTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }


    public function testInstance()
    {
        $id = uniqid();

        $documentFolderPath="\$root\\Tests\\Functional\\Fixures\\templates";

        $additionalData = [new AdditionalData("Document.ExportJPG","pageThumbnailPaths")];

        $clientEvent = "indesignserver.lowres.created";

        $response = new Response($id, Config::getInDesignServerWebhookClientUrls() , $additionalData, $clientEvent);

        /***
         * Build command
         */
        $classname = "Document.ExportJPG";

        $uuid = "c2335ce8-7000-4287-8972-f355ed23bd7f";

        $version = "Document.ExportJPG";

        $extension="indd";

        $exportFolderPath = "exportFolderPath";

        $commands = [new DocumentExportJPG($classname, $uuid,$extension, $version, $exportFolderPath)];

        $documentExportJPGTicket = new Ticket($id, $documentFolderPath ,$response, $commands);

        $this->assertEquals($documentExportJPGTicket->id, $id);

        $this->assertEquals($documentExportJPGTicket->documentFolderPath, $documentFolderPath);

        $this->assertEquals($documentExportJPGTicket->response, $response);

        $this->assertEquals($documentExportJPGTicket->commands, $commands);

        $jsonTicket = $documentExportJPGTicket->toJson();

        $this->assertNotEmpty($jsonTicket);

        $this->assertNotEmpty(json_decode($jsonTicket));

    }

    public function tearDown()
    {
        parent::tearDown();
    }

}