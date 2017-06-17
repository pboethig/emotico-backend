<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 11:46
 */

namespace Mittax\MediaConverterBundle\Websocket;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\Format;

class PusherTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testPush()
    {
        /**@var $service \Mittax\MediaConverterBundle\Websocket\Pusher*/
        $service = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $service->push(['testMessage from: ' . __METHOD__], 'mittax_mediaconverter.topic.converter.success', []);

        $service->push(['testMessage from: ' . __METHOD__], 'mittax_mediaconverter.topic.system.error', []);

        $this->assertNotNull($service);
    }
}