<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.12.16
 * Time: 19:51
 */

namespace Mittax\MediaConverterBundle\Entity\Storage;

use Mittax\MediaConverterBundle\Traits\Creation\Construct;
use Mittax\ObjectCollection\ICollectionItem;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 * Class StorageItemAbstract
 * @package Mittax\MediaConverterBundle\Entity\Metadata
 */
abstract class StorageItemAbstract implements ICollectionItem, IStorageItem
{
    /**
     * @var string
     */
    private $_dirname = '';

    /**
     * @var string
     */
    private $_basename = '';

    /**
     * @var string
     */
    private $_path = '';

    /**
     * @var int
     */
    private $_size = 0;

    /**
     * @var int
     */
    private $_timestamp = 0;

    /**
     * @var string
     */
    private $_filename = '';

    /**
     * @var string
     */
    private $_type = '';

    /**
     * @var string
     */
    private $_extension = '';

    /**
     * @var Uuid
     */
    private $_uuid;

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

        $this->_uuid = md5($rawData['filename']);

        $this->constructByKeyValue($rawData);
    }

    /**
     * @return string
     */
    public function getDirname() : string
    {
        return $this->_dirname;
    }

    /**
     * @return string
     */
    public function getPath() : string
    {
        return $this->_path;
    }

    /**
     * @return int
     */
    public function getSize() : int
    {
        return $this->_size;
    }

    /**
     * @return int
     */
    public function getTimestamp() : int
    {
        return $this->_timestamp;
    }

    /**
     * @return string
     */
    public function getBasename() : string
    {
        return $this->_basename;
    }

    /**
     * @return string
     */
    public function getFilename() : string
    {
        return $this->_filename;
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getExtension() : string
    {
        return $this->_extension;
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
}