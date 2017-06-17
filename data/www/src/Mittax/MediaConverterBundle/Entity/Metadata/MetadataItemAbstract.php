<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.12.16
 * Time: 19:51
 */

namespace Mittax\MediaConverterBundle\Entity\Metadata;

use Mittax\MediaConverterBundle\Traits\Creation\Construct;
use Mittax\ObjectCollection\ICollectionItem;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * Class StorageItemAbstract
 * @package Mittax\MediaConverterBundle\Entity\Metadata
 */
abstract class MetadataItemAbstract implements ICollectionItem, IMetadataItem
{
    /**
     * @var Uuid
     */
    public $_uuid;

    /**
     * @var array
     */
    private $_rawData = [];

    /**
     * @var Construct
     */
    use Construct;

    /**
     * StorageItemAbstract constructor.
     * @param array $rawData
     */
    public function __construct(Array $rawData)
    {
        $this->_rawData = $rawData;

        $this->_uuid = uniqid();

        $this->constructByKeyValue($this->_rawData);
    }

    /**
     * @return array
     */
    public function getRawData() : Array
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
     * @return string
     */
    public function toJson() : string
    {
        return json_encode($this);
    }
}