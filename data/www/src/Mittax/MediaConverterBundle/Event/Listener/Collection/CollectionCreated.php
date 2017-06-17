<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Collection;


use Symfony\Component\EventDispatcher\Event;
use Mittax\MediaConverterBundle\Event\Listener\IListener;

/**
 * Class Created
 * @package Mittax\MediaConverterBundle\Event\Listener\Collection
 */
class CollectionCreated implements IListener
{
    /**
     * @param Event $event
     */
    public function onCollectionCreated(Event $event)
    {
        
    }
}