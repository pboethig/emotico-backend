<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 18:01
 */

namespace Mittax\MediaConverterBundle\ValueObjects;

/**
 * Class Version
 * @package Mittax\MediaConverterBundle\ValueObjects
 */
class Version
{
    /**
     * @var int
     */
    private $_major = 0;

    /**
     * @var int
     */
    private $_minor = 0;

    /**
     * @var int
     */
    private $_patch = 0;

    /**
     * @var string
     */
    private $_versionString;

    /**
     * Version constructor.
     * @param string $versionString
     */
    public function __construct(string $versionString)
    {
        $this->_versionString = $versionString;

        $versions = explode('.',$versionString);

        $this->_major = $versions[0];

        $this->_minor = $versions[1];

        $this->_patch = $versions[2];
    }

    /**
     * @return int
     */
    public function getMajor()
    {
        return $this->_major;
    }

    /**
     * @return int
     */
    public function getMinor()
    {
        return $this->_minor;
    }

    /**
     * @return int
     */
    public function getPatch()
    {
        return $this->_patch;
    }

    /**
     * @return string
     */
    public function getVersionString()
    {
        return $this->_versionString;
    }
}