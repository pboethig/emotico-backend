<?php

namespace Mittax\MediaConverterBundle\Tests\Event\Message;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class LowresCreatedTest
 * @package Mittax\MediaConverterBundle\Tests\Event\Converter
 */
class LowresCreatedTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $clientEvent = "lowres.created";
        
        $ticketId = "1";

        $uuid = uniqid();

        $version = "version";

        $thumbnailList = [];

        $errors = [];

        $extension = "indd";

        $responseMessage = new \Mittax\MediaConverterBundle\Event\Listener\Messages\LowresCreated($clientEvent, $ticketId,  $uuid, $version, $extension, $thumbnailList, $errors);

        $this->assertEquals($clientEvent, $responseMessage->clientEvent);

        $this->assertEquals($ticketId, $responseMessage->ticketId);

        $this->assertEquals($uuid, $responseMessage->uuid);

        $this->assertEquals($version, $responseMessage->version);

        $this->assertEquals($thumbnailList, $responseMessage->thumbnailList);

        $this->assertEquals($errors, $responseMessage->errors);

        $this->assertEquals($extension, $responseMessage->extension);
    }
}