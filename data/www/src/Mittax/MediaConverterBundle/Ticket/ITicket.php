<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 27.12.16
 * Time: 15:58
 */

namespace Mittax\MediaConverterBundle\Ticket;


interface ITicket
{
    /**
     * @return string
     */
    public function serialize() : string ;

    /**
     * @return string
     */
    public function getJobId() : string ;
}