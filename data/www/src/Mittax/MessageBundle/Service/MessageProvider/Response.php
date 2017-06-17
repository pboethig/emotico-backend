<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.11.16
 * Time: 18:52
 */

namespace Mittax\MessageBundle\Service\MessageProvider;

/**
 * Class Response
 * @package Mittax\MessageBundle\Service\MessageProvider
 */
class Response implements IResponse
{
    /**
     * @var object
     */
    private $_response;

    /**
     * @var string
     */
    private $_message;

    /**
     * @var string
     */
    private $_status;

    /**
     * Response constructor.
     * @param $serviceResponse
     */
    public function __construct($serviceResponse)
    {
        $this->_response = $serviceResponse;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->_message = $message;

        return $this;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status)
    {
        $this->_status = $status;

        return $this;
    }

    /**
     * @return object
     */
    public function getOriginalResponse()
    {
        return $this->_response;
    }

    /**
     * @return string
     */
    public function getStatus() : string
    {
        return $this->_status;
    }

    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->_message;
    }
}