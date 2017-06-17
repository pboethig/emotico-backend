<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Thumbnail;

use Mittax\MediaConverterBundle\Event\Listener\IListener;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class JobTicketFineDataCreated implements IListener
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
     * @param \Mittax\MediaConverterBundle\Event\Thumbnail\JobTicketFineDataCreated $event
     */
    public function onThumbnailJobticketFinedataCreated(\Mittax\MediaConverterBundle\Event\Thumbnail\JobTicketFineDataCreated $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $storageItem = $event->getJobTicket()->getStorageItem();

        $message = new \Mittax\MediaConverterBundle\Event\Listener\Messages\ThumbnailTicketCreated(
            $event::NAME,
            $event->getJobTicket()->getJobId(),
            Filesystem::getUuidFromPath($storageItem->getDirname()),
            $storageItem->getFilename(),
            $storageItem->getExtension(),
            []
        );

        $pusher->push($message->toArray(), 'mittax_mediaconverter.topic.converter.ticketcreated', ['username' => 'user1']);
    }
}