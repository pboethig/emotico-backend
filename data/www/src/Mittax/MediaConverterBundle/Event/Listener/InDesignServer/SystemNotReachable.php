<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\InDesignServer;

use Mittax\MediaConverterBundle\Event\InDesignServer\SystemNotReachable;
use Mittax\MediaConverterBundle\Event\Listener\Messages\SystemError;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mittax\MediaConverterBundle\Event\Listener\IListener;
/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class InDesignServerLowresCreated implements IListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * InDesignServerLowresCreated constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param SystemNotReachable $event
     */
    public function onSystemNotReachable(SystemNotReachable $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $message = new SystemError(
            $event->getException()
        );

        $pusher->push($message->toArray(), 'mittax_mediaconverter.topic.system.error', ['username' => 'user1']);
    }
}