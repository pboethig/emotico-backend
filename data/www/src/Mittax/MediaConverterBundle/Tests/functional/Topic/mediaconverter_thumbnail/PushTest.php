<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 30.12.16
 * Time: 21:11
 */

namespace Mittax\MediaConverterBundle\Tests\Topic;


use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class PushTest extends AbstractKernelTestCase
{
    public function testPush()
    {
        $pusher = $this->container->get('gos_web_socket.wamp.pusher');

        $this->assertNotNull($pusher);

        //push(data, route_name, route_arguments)
        $pusher->push(['message' =>':' . __METHOD__ ], 'mittax_mediaconverter.topic.converter.success', ['username' => 'user1']);

        $pusher->push(['message' =>':' . __METHOD__ ], 'mittax_mediaconverter.topic.converter.ticketcreated', ['username' => 'user1']);

        $pusher->push(['message' =>':' . __METHOD__ ], 'mittax_mediaconverter.topic.converter.error', ['username' => 'user1']);
    }
}