<?php

namespace Mittax\MediaConverterBundle\Event\Listener\Messages;

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.05.17
 * Time: 19:52
 */
class LowresCreated
{
    /**
     * @var string
     */
    public $clientEvent = "";

    /**
     * @var string
     */
    public $ticketId = "";

    /**
     * @var string
     */
    public $uuid = "";

    /**
     * @var string
     */
    public $version="";

    /**
     * @var string
     */
    public $extension = "";

    /**
     * @var array
     */
    public $thumbnailList = [];

    /**
     * @var array
     */
    public $errors=[];


    public function _construct(string $clientEvent, string $ticketId, string $uuid, string $version, string $extension, array $thumbnailList = [], array $errors = [] )
    {

        $this->clientEvent = $clientEvent;

        $this->ticketId = $ticketId;

        $this->uuid = $uuid;

        $this->version = $version;

        $this->extension = $extension;

        $this->thumbnailList = $thumbnailList;

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'message' =>
                [
                    'event' => $this->clientEvent,
                    'ticketId' => $this->ticketId,
                    'uuid' => $this->uuid,
                    'version' => $this->version,
                    'extension' => $this->extension,
                    'thumbnailList' => $this->thumbnailList,
                    'errors'=> $this->errors
                ]
        ];
    }
}