<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 18.12.16
 * Time: 18:33
 */

namespace Mittax\MediaConverterBundle\Tests\BlackFire;


use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use \Mittax\MediaConverterBundle\Service\BlackFire\Client as BlackFireClient;
class Client extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testConstructor()
    {
        new \Mittax\MediaConverterBundle\Service\BlackFire\Client();
    }

    public function testClientInstance()
    {
        $this->assertInstanceOf(\Blackfire\Client::class, BlackFireClient::getClient());
    }

    public function testGetConfig()
    {
        $this->assertNotEmpty(BlackFireClient::getConfig());
    }

    public function testGetClientId()
    {
        $this->assertNotEmpty(BlackFireClient::getClientId());
    }

    public function testGetClientToken()
    {
      $this->assertNotEmpty(BlackFireClient::getClientToken());
    }
}