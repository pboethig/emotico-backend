<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Converter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Mittax\MediaConverterBundle\Event\Listener\IListener;
/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Listener\Thumbnail
 */
class NoConverterForFormatFoundException implements IListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Mittax\MediaConverterBundle\Event\Converter\NoConverterForFormatFoundException $event
     */
    public function onNoConverterForFormatFoundException(\Mittax\MediaConverterBundle\Event\Converter\NoConverterForFormatFoundException $event)
    {
        $pusher = $this->container->get('mittax_mediaconverter.websocket.pusher');

        $storageItem = $event->getStorageItem();

        $message = [
            'message' =>
                [
                    'message'=> ['error'=>__CLASS__],
                    'uuid' => $storageItem->getFilename(),
                    'filename' => $storageItem->getFilename(),
                    'extension' => $storageItem->getExtension()
                ]
        ];

        $pusher->push($message, 'mittax_mediaconverter.topic.converter.error', ['username' => 'user1']);
    }
}