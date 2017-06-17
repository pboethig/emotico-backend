<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 19.12.16
 * Time: 18:48
 */

namespace Mittax\MediaConverterBundle\Tests\Service\Cache;

use Mittax\MediaConverterBundle\Service\Cache\RedisWrapper;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use \Predis\Client;

/**
 * Class RedisWrapperTest
 * @package Mittax\MediaConverterBundle\Tests\Service\Cache
 */
class RedisWrapperTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testClient()
    {
        $client = RedisWrapper::getCacheClient();

        $this->assertInstanceOf(Client::class, $client);
    }
}