<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 09.12.16
 * Time: 23:30
 */

namespace Mittax\RabbitMQBundle\Service\Consumer;


interface IConsumer
{
    /**
     * @return array
     */
    public function getRawConfig() : Array;

    /**
     * @return string
     */
    public function getQueue() : string;

    /**
     * @return string
     */
    public function getConsumerTag() : string;

    /**
     * @return boolean
     */
    public function isNoLocal() : bool;

    /**
     * @return boolean
     */
    public function isNoAck() : bool;

    /**
     * @return boolean
     */
    public function isExclusive() : bool;

    /**
     * @return boolean
     */
    public function isNowait() : bool;

    /**
     * @return callable
     */
    public function getCallback() : callable;

    /**
     * @return int
     */
    public function getTicket() : int;

    /**
     * @return array
     */
    public function getArguments() : Array;
}