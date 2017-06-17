<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.12.16
 * Time: 22:32
 */

namespace Mittax\MediaConverterBundle\Service\Storage;

use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;

/**
 * Interface IResolver
 * @package Mittax\MediaConverterBundle\Service\Storage
 */
interface IResolver
{
    /**
     * @return string
     */
    public function getStoragePath() : string;

    /**
     * @return array
     */
    public function getConfig() : Array;

    /**
     * @return Filesystem
     */
    public function getFileSystemWrapper() : Filesystem;

    /**
     * @return IAdapter
     */
    public function getCachedFilesystemAdapter() : IAdapter;
}