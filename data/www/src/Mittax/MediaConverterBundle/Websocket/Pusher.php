<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 30.12.16
 * Time: 22:01
 */

namespace Mittax\MediaConverterBundle\Websocket;


class Pusher
{

    private $pusher;

    public function __construct($pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @param array $message
     * @param string $eventName
     * @param array $arguments
     */
    public function push(array $message, string $eventName, array $arguments)
    {
        try
        {
            $this->pusher->push($message, $eventName, $arguments);
        }
        catch (\Exception $ex)
        {

        }
    }
}