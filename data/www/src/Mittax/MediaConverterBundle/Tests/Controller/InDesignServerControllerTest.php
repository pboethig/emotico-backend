<?php

namespace Mittax\MediaConverterBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InDesignServerControllerTest extends WebTestCase
{
    public function testPing()
    {
        $client = static::createClient();

        $client->request('GET', '/indesignserver/ping');

        $response = json_decode($client->getResponse()->getContent());

        $this->assertNotEmpty($response->IP);
    }
}
