<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 22:09
 */

namespace Mittax\MediaConverterBundle\ValueObjects;
use Mittax\MediaConverterBundle\Traits\Creation\Construct;
use Mittax\ObjectCollection\ICollectionItem;

/**
 * Class Format
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\ValueObjects
 */
class Format implements ICollectionItem
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_extension;

    /**
     * @var string
     */
    private $_mimeType;

    /**
     * @var string
     */
    private $_type;

    /**
     * @var string
     */
    private $_uuid;

    use Construct;

    public function __construct(Array $rawData)
    {
        $this->constructByKeyValue($rawData);

        $this->_uuid = $this->buildUuid($this);
    }

    /**
     * @param Format $format
     * @return string
     */
    public function buildUuid(Format $format)
    {
        $generator = new \Mittax\MediaConverterBundle\Service\Uuid\Format($format);

        return $generator->generate();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
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
     * @return string
     */
    public function getType()
    {
        return $this->_type;
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
}