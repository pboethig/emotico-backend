<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 10:30
 */

namespace Mittax\MediaConverterBundle\Event\InDesignServer;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Exception\InDesignServerNotAvailableException;
use Mittax\MediaConverterBundle\Ticket\InDesignServer\Types\Response;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NoConverterForFormatFoundExceptionextends
 * @package Mittax\MediaConverterBundle\Event\Converter
 */
class SystemNotReachable extends Event
{
    const NAME = 'indesignserver.systemnotreachable';

    /**
     * @var InDesignServerNotAvailableException
     */
    protected $exception;

    /**
     * SystemNotReachable constructor.
     * @param InDesignServerNotAvailableException $exception
     */
    public function __construct(InDesignServerNotAvailableException $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return Response
     */
    public function getException()
    {
        return $this->exception;
    }
}