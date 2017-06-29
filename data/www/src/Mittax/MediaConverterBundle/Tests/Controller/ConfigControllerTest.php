<?php

namespace Mittax\MediaConverterBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AssetControllerTest
 * @package Mittax\MediaConverterBundle\Tests\Controller
 */
class ConfigControllerTest extends WebTestCase
{
    public function testUpload()
    {
        $client = static::createClient();

        $client->request('GET', '/config/get');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
