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
class BrowserImageData
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
     * @var float
     */
    private $aspectRatio = 0.0;

    /**
     * @var int
     */
    private $naturalHeight = 0;

    /**
     * @var int
     */
    private $naturalWidth = 0;

    /**
     * CroppingData constructor.
     */
    public function __construct(\stdClass $browserImageData)
    {

        //imagemackig only accepts integer
        $this->width = (int)$browserImageData->width;

        $this->height = (int)$browserImageData->height;

        $this->top = (int)$browserImageData->top;

        $this->left = (int)$browserImageData->left;

        $this->naturalHeight = (int)$browserImageData->naturalHeight;

        $this->naturalWidth = $browserImageData->naturalWidth;

        $this->aspectRatio = $browserImageData->aspectRatio;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * @return int
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
     * @return float
     */
    public function getAspectRatio()
    {
        return $this->aspectRatio;
    }

    /**
     * @return int
     */
    public function getNaturalHeight()
    {
        return $this->naturalHeight;
    }

    /**
     * @return int
     */
    public function getNaturalWidth()
    {
        return $this->naturalWidth;
    }
}