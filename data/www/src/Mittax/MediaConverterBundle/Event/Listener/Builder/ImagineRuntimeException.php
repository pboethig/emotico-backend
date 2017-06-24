<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 31.12.16
 * Time: 20:55
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Builder;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;

class ImagineRuntimeException extends Event
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
     * @param \Mittax\MediaConverterBundle\Event\Builder\ImagineRuntimeException $event
     */
    public function onThumbnailBuilderImagineRuntimeexception(\Mittax\MediaConverterBundle\Event\Builder\ImagineRuntimeException $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $message = [
                    'message' =>
                        [
                            'errors' => [$event->getException()->getMessage().$event->getException()->getTraceAsString()],
                            'eventName'=>$event::NAME,
                            'uuid' => $event->getTicket()->getStorageItem()->getUuid(),
                            'ticketId' => $event->getTicket()->getJobId(),
                            'filename' => $event->getTicket()->getStorageItem()->getFilename(),
                            'extension' => $event->getTicket()->getStorageItem()->getExtension()
                        ]
                    ];

        $pusher->push($message, 'mittax_mediaconverter.topic.converter.error', ['username' => 'user1']);
    }
}