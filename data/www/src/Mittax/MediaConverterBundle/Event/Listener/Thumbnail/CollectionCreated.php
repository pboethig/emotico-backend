<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Thumbnail;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Mittax\MediaConverterBundle\Event\Listener\IListener;
/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class CollectionCreated implements IListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Event $event
     */
    public function onThumbnailCollectionCreated(Event $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        //push(data, route_name, route_arguments)
        $pusher->push(['message' => 'yeaaaa' . __METHOD__ ], 'mediaconverter_thumbnail', ['username' => 'user1']);
    }
}