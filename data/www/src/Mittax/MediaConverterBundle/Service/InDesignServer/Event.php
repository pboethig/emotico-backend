<?php
namespace Mittax\MediaConverterBundle\Service\InDesignServer;

use Mittax\MediaConverterBundle\Event\InDesignServer\InDesignServerError;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use Mittax\MediaConverterBundle\Service\System\Config;
use Symfony\Component\EventDispatcher\EventDispatcher;
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 26.05.17
 * Time: 21:16
 */
class Event
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * Event constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Response $inDesignServerResponse
     */
    public function dispatch(Response $inDesignServerResponse)
    {
        $eventClass = Config::getEventClass($inDesignServerResponse->clientEvent);

        if(count($inDesignServerResponse->errors))
        {
            $this->dispatchErrorEvent($inDesignServerResponse);

            return true;
        }

        $event = new $eventClass($inDesignServerResponse);

        $dispatcher = new EventDispatcher();

        $eventListenerClassName = Config::getEventListenerClass($inDesignServerResponse->clientEvent);

        $listener = new $eventListenerClassName($this->container);

        $dispatcher->addListener($inDesignServerResponse->clientEvent, array($listener, Config::getEventListenerMethodName($inDesignServerResponse->clientEvent)));

        $dispatcher->dispatch($inDesignServerResponse->clientEvent, $event);
    }

    /**
     * @param Response $indesignServerResponse
     */
    public function dispatchErrorEvent(Response $indesignServerResponse)
    {
        $dispatcher = new EventDispatcher();

        $listener = new \Mittax\MediaConverterBundle\Event\Listener\InDesignServer\InDesignServerError($this->container);

        $event = new InDesignServerError($indesignServerResponse);

        $dispatcher->addListener(InDesignServerError::NAME, array($listener, 'onInDesignServerError'));

        $dispatcher->dispatch(InDesignServerError::NAME, $event);
    }

    /**
     * @param string $jsonResponse
     * @return Response
     */
    public function buildTypedResponseFromJson(string $jsonResponse)
    {
        $responseObject = json_decode($jsonResponse);

        $response = new Response(
            $responseObject->ticketId,
            $responseObject->urls,
            $responseObject->additionalData,
            $responseObject->clientEvent,
            $responseObject->status,
            $responseObject->ticket,
            $responseObject->errors
        );

        return $response;
    }
}