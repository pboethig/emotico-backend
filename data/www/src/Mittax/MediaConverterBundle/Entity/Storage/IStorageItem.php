<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.12.16
 * Time: 19:47
 */

namespace Mittax\MediaConverterBundle\Entity\Storage;
use Mittax\ObjectCollection\ICollectionItem;

/**
 * Interface IStorageItem
 * @package Mittax\MediaConverterBundle\Entity\Metadata
 */
interface IStorageItem extends ICollectionItem
{
    /**
     * @return string
     */
    public function getDirname() : string;

    /**
     * @return string
     */
    public function getPath() : string;

    /**
     * @return int
     */
    public function getSize() : int;

    /**
     * @return int
     */
    public function getTimestamp() : int;

    /**
     * @return string
     */
    public function getBasename() : string;

    /**
     * @return string
     */
    public function getFilename() : string;

    /**
     * @return string
     */
    public function getType() : string;

    /**
     * @return string
     */
    public function getExtension() : string;

    /**
     * @return array
     */
    public function getRawData() : Array;

    /**
     * @return string
     */
    public function getUuid() : string;

}