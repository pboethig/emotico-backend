<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 23:21
 */

namespace Mittax\MediaConverterBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Kernel;

class Dispatcher
{

    /**
     * instance
     *
     * @var EventDispatcher
     */
    protected static $_instance = null;

    /**
     * get instance
     * @return   EventDispatcher
     */
    public static function getInstance()
    {
        if (null === self::$_instance)
        {
            $appkernel = new \AppKernel("prod", false);

            $appkernel->boot();

            self::$_instance = $appkernel->getContainer()->get('event_dispatcher');
        }

        return self::$_instance;
    }

    protected function __clone() {}

    protected function __construct() {}

    /**
     * @param string $className
     * @param Event $event
     * @return Event
     */
    public static function dispatch(string $className, Event $event)
    {
        return self::getInstance()->dispatch($className, $event);
    }
}