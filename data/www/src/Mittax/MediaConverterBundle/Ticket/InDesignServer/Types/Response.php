<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 24.05.17
 * Time: 19:34
 */
namespace Mittax\MediaConverterBundle\Ticket\InDesignServer\Types;
/**
 * Class Response
 * @package Mittax\MediaConverterBundle\Ticket\InDesignServer\Types
 */
class Response
{
    /**
     * @var string
     */
    public $ticketId="";

    /**
     * @var string
     */
    public $status="open";

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var array
     */
    public $urls = [];

    /**
     * @var string
     */
    public $clientEvent="";

    /**
     * @var AdditionalData[]
     */
    public $additionalData = [];

    /**
     * @var \stdClass
     */
    public $originalTicket;
    /**
     * Response constructor.
     * @param string $ticketId
     * @param array $urls
     * @param array $additionalData
     */
    public function __construct(string $ticketId, array $urls, array $additionalData = [], string $clientEvent, string $status='open',  \stdClass $originalTicket = null, array $errors = [])
    {
        $this->ticketId = $ticketId;

        $this->urls = $urls;

        $this->additionalData = $additionalData;

        $this->clientEvent = $clientEvent;

        $this->originalTicket = $originalTicket;

        $this->status = $status;

        $this->errors = $errors;
    }
}