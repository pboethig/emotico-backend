<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 10:30
 */

namespace Mittax\MediaConverterBundle\Event\InDesignServer;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NoConverterForFormatFoundExceptionextends
 * @package Mittax\MediaConverterBundle\Event\Converter
 */
class InDesignServerLowresCreated extends Event
{
    const NAME = 'indesignserver.lowres.created';

    /**
     * @var Response
     */
    protected $inDesignServerResponse;

    /**
     * InDesignServerLowresCreated constructor.
     * @param Response $inDesignServerResponse
     */
    public function __construct(Response $inDesignServerResponse)
    {
        $this->inDesignServerResponse = $inDesignServerResponse;
    }

    /**
     * @return Response
     */
    public function getInDesignServerResponse()
    {
        return $this->inDesignServerResponse;
    }
}