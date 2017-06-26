<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Converter\Imagine;

use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Service\WebHook\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mittax\MediaConverterBundle\Event\Listener\IListener;
/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class HiresCroppingCreated implements IListener
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
     * @param \Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingCreated $event
     */
    public function onImagineHiresCroppingCreated(\Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingCreated $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $storageItem = $event->getJobTicket()->getStorageItem();

        $message = ['message' =>
            [
                'event' => $event::NAME,
                'ticketId' => $event->getJobTicket()->getJobId(),
                'uuid' => $event->getJobTicket()->getJobId(),
                'version' => $storageItem->getBasename(),
                'extension' => $storageItem->getExtension(),
                'hash' => $event->getJobTicket()->getCroppingData()->getHash(),
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
}