<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.11.16
 * Time: 15:18
 */

namespace Mittax\MessageBundle\Service\MessageProvider\Sms\Twillo;

/**
 * Class ClientConfiguration
 * @package Mittax\MessageBundle\Service\MessageProvider\Twillo\Sms
 */
class ClientConfiguration
{
    /**
     * @var int
     */
    private $_sid;

    /**
     * @var string
     */
    private $_authToken;

    /**
     * @var string
     */
    private $_number;

    /**
     * Configuration constructor.
     * @param string $sid
     * @param string $authToken
     * @param string $number
     */
    public function __construct(string $sid, string $authToken, string $number)
    {
        $this->_sid = $sid;

        $this->_authToken = $authToken;

        $this->_number =  $number;
    }

    /**
     * @return int
     */
    public function getSid()
    {
        return $this->_sid;
    }

    /**
     * @param int $sid
     */
    public function setSid($sid)
    {
        $this->_sid = $sid;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->_authToken;
    }

    /**
     * @param string $authToken
     */
    public function setAuthToken($authToken)
    {
        $this->_authToken = $authToken;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->_number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->_number = $number;
    }
}