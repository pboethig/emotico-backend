<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 02:12
 */

namespace Mittax\MediaConverterBundle\Ticket;

/**
 * Class JobAbstract
 * @package Mittax\MediaConverterBundle\ThumbnailTicket
 */
abstract class TicketAbstract implements ITicket
{

    /**
     * @var string
     */
    protected $_jobId;

    /**
     * JobAbstract constructor.
     * @param ITicketBuilder $ticketBuilder
     */
    public function __construct(ITicketBuilder $ticketBuilder = null)
    {
        $this->_jobId = uniqid();
    }

    /**
     * @return string
     */
    public function serialize() : string
    {
        return serialize($this);
    }

    /**
     * @return string
     */
    public function getJobId() : string
    {
        return $this->_jobId;
    }
}