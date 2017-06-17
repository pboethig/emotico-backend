<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.01.17
 * Time: 10:28
 */

namespace Mittax\MediaConverterBundle\Service\Metadata\Generator;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;

/**
 * Interface IMetadataGenerator
 * @package Mittax\MediaConverterBundle\Service\Metadata\Generator
 */
interface IMetadataGenerator
{
    /**
     * Step 0) Store storageItem
     *
     * IMetadataGenerator constructor.
     * @param StorageItem $storageItem
     */
    public function __construct(StorageItem $storageItem);

    /**
     * Step 1) Check generator if it supports current format
     *
     * @return bool
     */
    public function doesSupportsFormat() : bool;

    /**
     * Step 2) Build temporary filepath to extract metadata from it
     *
     * @return string
     */
    public function buildTempFilePath() : string;

     /**
      * Step 3) Now copy the asset to tempfolder
      *
      * @return string
      */
    public function copyAssetToTempFolder() : string;

    /**
     * Step 4) Extract metadata from temp file
     *
     * @return \PHPExif\Exif
     */
    public function extractMetadataFromTempFile();

    /**
     * Step 5) Now store extracted metacata as json in storage
     *
     * @return string
     */
    public function storeMetadataAsJsonFile() : string;

    /**
     * @return mixed
     */
    public function read() : Array;

    /**
     * @return string
     */
    public function getStoragePath() : string;
}