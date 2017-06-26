<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 31.12.16
 * Time: 20:55
 */

namespace Mittax\MediaConverterBundle\Event\Converter\Imagine;


use Imagine\Exception\RuntimeException;
use Mittax\MediaConverterBundle\Ticket\Cropping\ICroppingTicket;
use Symfony\Component\EventDispatcher\Event;

class HiresCroppingException extends Event
{

    const NAME = 'imagine.hires.cropping.exception';

    /**
     * @var \Imagine\Exception\RuntimeException
     */
    private $_exception;

    /**
     * @var ICroppingTicket
     */
    private $_ticket;

    /**
     * HiresCroppingException constructor.
     * @param \Exception $exception
     * @param ICroppingTicket $croppingTicket
     */
    public function __construct(\Exception $exception,ICroppingTicket $croppingTicket)
    {
        $this->_exception = $exception;

        $this->_ticket = $croppingTicket;
    }

    /**
     * @return RuntimeException
     */
    public function getException()
    {
        return $this->_exception;
    }

    /**
     * @return ICroppingTicket
     */
    public function getTicket()
    {
        return $this->_ticket;
    }
}