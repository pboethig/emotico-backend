<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 22:09
 */

namespace Mittax\MediaConverterBundle\ValueObjects;
use Mittax\MediaConverterBundle\Traits\Creation\Construct;

/**
 * Class Format
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ValueObjects
 */
class Box
{
    /**
     * @var int
     */
    private $width = 0;

    /**
     * @var
     */
    private $height = 0;

    /**
     * @var string
     */
    private $_uuid;

    public function __construct($width, $height)
    {
        $this->width = $width;

        $this->height = $height;

        $this->_uuid = uniqid();
    }

    /**
     * @return string
     */
    public function getUuid() : string
    {
        return $this->_uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->_uuid = $uuid;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    
}