<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Converter\Imagine;

use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Service\WebHook\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mittax\MediaConverterBundle\Event\Listener\IListener;
/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class HiresCroppingException implements IListener
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
     * @param \Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingException $event
     */
    public function onImagineHiresCroppingException(\Mittax\MediaConverterBundle\Event\Converter\Imagine\HiresCroppingException $event)
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