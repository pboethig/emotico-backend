<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 19:49
 */

namespace Mittax\MediaConverterBundle\Entity\Thumbnail;

use Mittax\MediaConverterBundle\Traits\Creation\Construct;
use Mittax\ObjectCollection\ICollectionItem;

/**
 * Class Thumbnail
 * @package Mittax\MediaConverterBundle\Entity\Thumbnail
 */
class Thumbnail implements ICollectionItem
{
    /**
     * @var string
     */
    private $_sourcePath;

    /**
     * @var string
     */
    private $_targetPath;

    /**
     * @var integer
     */
    private $_resolution;

    /**
     * @var integer
     */
    private $_width;

    /**
     * @var integer
     */
    private $_height;

    /**
     * @var string
     */
    private $_extension;

    /**
     * @var string
     */
    private $_mimeType;

    /**
     * @var array
     */
    private $_rawData;

    /**
     * @var string
     */
    private $_uuid;

    /**
     * @var \Exception
     */
    private $_converterException;

    /**
     * @var Construct
     */
    use Construct;

    public function __construct(Array $rawData)
    {
        $this->_rawData = $rawData;

        $this->constructByKeyValue($rawData);

        $this->_uuid = $this->_targetPath;
    }

    /**
     * @return string
     */
    public function getSourcePath()
    {
        return $this->_sourcePath;
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->_targetPath;
    }

    /**
     * @return int
     */
    public function getResolution()
    {
        return $this->_resolution;
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
    public function getExtension()
    {
        return $this->_extension;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->_mimeType;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->_rawData;
    }

    /**
     * @return string
     */
    public function getUuid() : string
    {
        return $this->_uuid;
    }
    
    /**
     * @param string $sourcePath
     */
    public function setSourcePath($sourcePath)
    {
        $this->_sourcePath = $sourcePath;
    }

    /**
     * @param string $targetPath
     */
    public function setTargetPath($targetPath)
    {
        $this->_targetPath = $targetPath;
    }

    /**
     * @param int $resolution
     */
    public function setResolution($resolution)
    {
        $this->_resolution = $resolution;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->_height = $height;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->_extension = $extension;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->_mimeType = $mimeType;
    }

    /**
     * @return \Exception
     */
    public function getConverterException()
    {
        return $this->_converterException;
    }
}