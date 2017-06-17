<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 11.12.16
 * Time: 20:57
 */

namespace Mittax\RabbitMQBundle\Exception\Handler;


interface IConsumerException
{
    /**
     * @return \Exception
     */
    public function getException() : \Exception ;

    /**
     * @param \Exception $exception
     * @return bool
     */
    public function resolve(\Exception $exception) : bool;

}