<?php

namespace Mittax\MediaConverterBundle\Tests\Service\WebHook;

use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Service\WebHook\Client;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class ClientTest
 * @package Mittax\MediaConverterBundle\Tests\Service\Uuid
 */
class ClientTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testInstance()
    {
        $webhookClient = $this->container->get('mittax.mediaconverterbundle.webhook.client');

        $this->assertEquals(Client::class,get_class($webhookClient));
    }

    public function testCall()
    {
        $webhookClient = new Client();

        $clients = Config::getWebHook('thumbnail.finedata.created');

        $assetData = [
            'message'=>
            [
                'uuid'=> uniqid(),
                'version'=>"testfile",
                'extension'=>'jpg',
                'type'=>'image',
                'thumbnailList'=>['a test', 'another test']
            ]
        ];

        $responses = $webhookClient->call($assetData, $clients);

        $this->assertEquals(200, $responses[0]->getStatusCode());
    }
}