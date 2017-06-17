<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\InDesignServer;

use Mittax\MediaConverterBundle\Event\Listener\Messages\LowresCreated;
use Mittax\MediaConverterBundle\Service\InDesignServer\Document\Thumbnail;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Service\WebHook\Client;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Commands\InDesignServerCommandAbstract;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
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
     * @param \Mittax\MediaConverterBundle\Event\InDesignServer\InDesignServerLowresCreated $event
     */
    public function onIndesignServerLowresCreated(\Mittax\MediaConverterBundle\Event\InDesignServer\InDesignServerLowresCreated $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $indesignServerResponse = $event->getInDesignServerResponse();

        /** @var  $command InDesignServerCommandAbstract*/
        $command = $indesignServerResponse->originalTicket->commands[0];

        $thumbNailList = Thumbnail::buildThumbnailList($indesignServerResponse);

        $message = ['message' =>
            [
                'event' => $indesignServerResponse->clientEvent,
                'ticketId' => $indesignServerResponse->ticketId,
                'uuid' => $command->uuid,
                'version' => $command->version,
                'extension' => $command->extension,
                'thumbnailList' => $thumbNailList,
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