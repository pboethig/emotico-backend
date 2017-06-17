<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.11.16
 * Time: 18:52
 */

namespace Mittax\MessageBundle\Service\MessageProvider;


interface IResponse
{
    /**
     * @return string
     */
    public function getMessage() : string ;

    /**
     * @return string
     */
    public function getStatus() : string ;

    /**
     * @return mixed
     */
    public function getOriginalResponse();

    /**
     * @param string $message
     * @return mixed
     */
    public function setMessage(string $message);

    /**
     * @param string $status
     * @return mixed
     */
    public function setStatus(string $status);

}