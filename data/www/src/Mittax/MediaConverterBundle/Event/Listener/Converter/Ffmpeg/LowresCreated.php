<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Converter\Ffmpeg;


use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Mittax\MediaConverterBundle\Event\Listener\IListener;
/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class LowresCreated implements IListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ImagineRuntimeException constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Mittax\MediaConverterBundle\Event\Converter\Ffmpeg\LowresCreated $event
     */
    public function onFfmpegLowresCreated(\Mittax\MediaConverterBundle\Event\Converter\Ffmpeg\LowresCreated $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $storageItem = $event->getJobTicket()->getStorageItem();

        $message = new \Mittax\MediaConverterBundle\Event\Listener\Messages\LowresCreated(
            $event::NAME,
            $event->getJobTicket()->getJobId(),
            Filesystem::getUuidFromPath($storageItem->getDirname()),
            $storageItem->getFilename(),
            $storageItem->getExtension(),
            [Filesystem::convertStoragePathToUrl($event->getJobTicket()->getCurrentTargetStoragePath())],
            []
        );

        $pusher->push($message->toArray(), 'mittax_mediaconverter.topic.converter.success', ['username' => 'user1']);
    }
}