<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\InDesignServer;

use Mittax\MediaConverterBundle\Ticket\InDesignServer\Commands\InDesignServerCommandAbstract;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Mittax\MediaConverterBundle\Event\Listener\IListener;
/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class InDesignServerError implements IListener
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
     * @param \Mittax\MediaConverterBundle\Event\InDesignServer\InDesignServerError $event
     */
    public function onIndesignserverError(\Mittax\MediaConverterBundle\Event\InDesignServer\InDesignServerError $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $indesignServerResponse = $event->getInDesignServerResponse();

        /** @var  $command InDesignServerCommandAbstract*/
        $command = $indesignServerResponse->originalTicket->commands[0];

        $message = new \Mittax\MediaConverterBundle\Event\Listener\Messages\LowresCreated(
            $indesignServerResponse->clientEvent,
            $indesignServerResponse->ticketId,
            $command->uuid,
            $command->version,
            $command->extension,
            [],
            $indesignServerResponse->errors
        );

        $pusher->push($message->toArray(), 'mittax_mediaconverter.topic.converter.error', ['username' => 'user1']);
    }
}