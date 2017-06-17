<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.11.16
 * Time: 17:39
 */

namespace Mittax\MessageBundle\Service;

/**
 * Class PhoneNumber
 * @package Mittax\MessageBundle\Service
 */
class PhoneNumber
{

    /**
     * @var \libphonenumber\PhoneNumberUtil

     */
    private $_parser;


    public function __construct()
    {
        $this->setParser(\libphonenumber\PhoneNumberUtil::getInstance());
    }

    /**
     * @param string $phoneNumber
     * @param string $countryCode
     * @return bool
     */
    public function validate(string $phoneNumber, string $countryCode)
    {
        $phoneNumber = $this->_parser->parse($phoneNumber, $countryCode);

        return $this->_parser->isValidNumber($phoneNumber);
    }

    /**
     * @param \libphonenumber\PhoneNumber $phoneNumber
     * @return string
     */
    public function toInternational(\libphonenumber\PhoneNumber $phoneNumber)
    {
        return $this->_parser->format($phoneNumber, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
    }

    /**
     * @param string $phoneNumber
     * @param string $region
     * @return \libphonenumber\PhoneNumber
     */
    public function parse(string $phoneNumber, string $region)
    {
        return $this->_parser->parse($phoneNumber, $region);
    }

    /**
     * @param \libphonenumber\PhoneNumberUtil $parser
     */
    public function setParser($parser)
    {
        $this->_parser = $parser;
    }

    /**
     * @return \libphonenumber\PhoneNumberUtil
     */
    public function getParser()
    {
        return $this->_parser;
    }
}