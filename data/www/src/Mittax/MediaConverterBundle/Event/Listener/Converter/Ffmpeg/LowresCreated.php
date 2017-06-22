<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Converter\Ffmpeg;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Service\WebHook\Client;
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

        $thumbnail = $this->getThumbnailByStorageItem($event);

        $message = ['message' =>
            [
                'event' => $event::NAME,
                'ticketId' => $event->getJobTicket()->getJobId(),
                'uuid' => Filesystem::getUuidFromPath($storageItem->getDirname()),
                'version' => $storageItem->getBasename(),
                'extension' => $storageItem->getExtension(),
                'thumbnailList' => [$thumbnail],
                'errors'=> []
            ]
        ];

        $pusher->push($message, 'mittax_mediaconverter.topic.converter.success', ['username' => 'user1']);

        /**
         * Notify client backends
         */
        $webHookClient =  new Client();

        $webHookClient->call($message, Config::getWebHook($event::NAME));
    }

    /**
     * @param $event
     * @return mixed|string
     */
    private function getThumbnailByStorageItem(\Mittax\MediaConverterBundle\Event\Converter\Ffmpeg\LowresCreated $event)
    {
        $thumbnail = Filesystem::convertStoragePathToUrl($event->getJobTicket()->getCurrentTargetStoragePath());

        $thumbnail = str_replace(".jpg", '', $thumbnail);

        $thumbnail = $thumbnail . "_lowres.mp4";

        return $thumbnail;
    }
}