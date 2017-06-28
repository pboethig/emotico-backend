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
class CroppingData
{
    /**
     * @var int
     */
    private $width = 0;

    /**
     * @var int
     */
    private $height = 0;

    /**
     * @var int
     */
    private $top = 0;

    /**
     * @var int
     */
    private $left = 0;

    /**
     * @var string
     */
    private $messurement='px';

    /**
     * @var string
     */
    private $hash;

    /**
     * CroppingData constructor.
     */
    public function __construct(\stdClass $croppingData)
    {
        //imagemackig only accepts integer
        $this->width = (int)$croppingData->width;

        $this->height = (int)$croppingData->height;

        $this->top = (int)$croppingData->top;

        $this->left = (int)$croppingData->left;

        if(isset($croppingData->messurement)){
            $this->messurement = $croppingData->messurement;
        }

        if(isset($croppingData->hash)){
            $this->hash = $croppingData->hash;
        }
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return float
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * @return float
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @return string
     */
    public function getMessurement()
    {
        return $this->messurement;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}