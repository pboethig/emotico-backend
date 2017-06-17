<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 18:01
 */

namespace Mittax\MediaConverterBundle\ValueObjects;
use Mittax\MediaConverterBundle\Traits\Creation\Construct;

/**
 * Class Size
 * @package Mittax\MediaConverterBundle\ValueObjects
 */
class Size
{
    /**
     * @var int
     */
    private $_width = 0;

    /**
     * @var int
     */
    private $_height = 0;

    /**
     * @var string
     */
    private $_messurement = 'px';

    use Construct;

    /**
     * Size constructor.
     * @param array $rawData
     */
    public function __construct(Array $rawData)
    {
        $this->constructByKeyValue($rawData);
    }

    /**
     * @param array $rawData
     * @return Size[]
     */
    public static function fromSizesArray(Array $rawData)
    {
        $sizes = [];

        foreach ($rawData as $size)
        {
            array_push($sizes, new self($size));
        }

        return $sizes;
    }


    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @return string
     */
    public function getMessurement()
    {
        return $this->_messurement;
    }
}