<?php

namespace Mittax\MediaConverterBundle\Tests\Controller;

use Mittax\MediaConverterBundle\Service\System\Config;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QueueControllerTest extends WebTestCase
{
    public function testUpload()
    {
        $client = static::createClient();

        $queueName = "q_imagine_thumbnails";

        $crawler = $client->request('GET', '/queue/' . $queueName . '/info');

        $this->assertNotNull($crawler);

        $this->assertEquals($client->getResponse()->getStatusCode(), 200);
    }
}
