<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.12.16
 * Time: 21:31
 */
namespace Mittax\MediaConverterBundle\Service\Storage\Local;

use Mittax\MediaConverterBundle\Exception\FileSystemStoragePathDoesNotExistException;
use Mittax\MediaConverterBundle\Service\Storage\IResolver;
use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;

/**
 * Class Resolver
 * @package Mittax\MediaConverterBundle\Service\Storage\Local
 */
class Resolver implements IResolver
{
    /**
     * @var array
     */
    private $_config;

    /**
     * @var string
     */
    private $_storagePath='';

    /**
     * @var Filesystem
     */
    private $_fileSystemWrapper;

    /**
     * @var IAdapter
     */
    private $_cachedFilesystemAdapter;
    /**
     * Resolver constructor.
     * @param array $config
     */
    public function __construct(Array $config)
    {
        $this->_config = $config;

        $this->_storagePath = $this->getStoragePath();

        $this->_fileSystemWrapper = new Filesystem();

        $this->_cachedFilesystemAdapter = Filesystem::getCachedAdapter($this->_storagePath);
    }

    /**
     * @return string
     * @todo: fix ugly pathhandling
     */
    public function getStoragePath() : string
    {
        $rootPath = __DIR__ . '/../../../../../../';

        $path = str_replace('${root}/', $rootPath, $this->_config['path']);

        if(!is_dir($path)) throw new FileSystemStoragePathDoesNotExistException('FileStoragePath does not exist: ' . $path);

        return $path;
    }

    /**
     * @return array
     */
    public function getConfig() : Array
    {
        return $this->_config;
    }

    /**
     * @return Filesystem
     */
    public function getFileSystemWrapper() : Filesystem
    {
        return $this->_fileSystemWrapper;
    }

    /**
     * @return IAdapter
     */
    public function getCachedFilesystemAdapter() : IAdapter
    {
        return $this->_cachedFilesystemAdapter;
    }
}