<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 11:07
 */

namespace Mittax\MediaConverterBundle\Repository\Storage;

use Mittax\MediaConverterBundle\Collection\StorageItem as StorageItemCollection;
use Mittax\MediaConverterBundle\Exception\StoragePathListNotDefinedException;
use Mittax\MediaConverterBundle\Repository\IRepositoryConfiguration;
use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;
use Mittax\MediaConverterBundle\Service\Uuid\StorageItemRepositoryConfig;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
/**
 * Class StorageRepositoryConfig
 * @package Mittax\MediaConverterBundle\Repository\Storage
 */
class StorageRepositoryConfig implements IRepositoryConfiguration
{
    /**
     * @var string
     */
    private $_repositoryClassName = ItemRepository::class;

    /**
     * @var FilesystemResolverFactory
     */
    private $_fileSystemResolverFactory = null;

    /**
     * @var array
     */
    private $_itemPathList = [];

    /**
     * @var string
     */
    private $_uuid = '';

    /**
     * @var array
     */
    private static $_metadataCache = [];

    /**
     * @var IAdapter
     */
    private static $cachedFileSystemAdapter = null;

    /**
     * StorageRepositoryConfig constructor.
     * @param array $pathList
     * @param $testLocalFileSystemConfig
     */
    public function __construct(Array $pathList, $testLocalFileSystemConfig = [])
    {
        $this->_fileSystemResolverFactory = new FilesystemResolverFactory($testLocalFileSystemConfig);

        $this->_itemPathList = $pathList;

        $this->validate();

        $this->_uuid = $this->buildUuid($this);
    }

    /**
     * @param StorageRepositoryConfig $storageRepositoryConfig
     * @return string
     */
    public function buildUuid(StorageRepositoryConfig $storageRepositoryConfig) : string
    {
        return (new StorageItemRepositoryConfig($storageRepositoryConfig))->generate();
    }

    /**
     * @return bool
     */
    public function validate() : bool
    {
        if ( empty ( $this->_itemPathList ) )
        {
            throw new StoragePathListNotDefinedException('No pathlist available to init storage');
        }

        return true;
    }

    /**
     * @return StorageItemCollection
     */
    public function buildCollection() : StorageItemCollection
    {
        if (!self::$cachedFileSystemAdapter)
        {
            self::$cachedFileSystemAdapter = $this->_fileSystemResolverFactory->getResolver()->getCachedFilesystemAdapter();
        }

        $collection = $this->getCachedCollectionFromPathList($this->getItemPathList());

        return $collection;
    }

    /**
     * @param array $pathList
     * @return StorageItemCollection
     */
    public function getCachedCollectionFromPathList(Array $pathList) : StorageItemCollection
    {
        $itemList = [];

        foreach ($pathList as $itemPath)
        {

            $metadata = self::$cachedFileSystemAdapter->getMetadata($itemPath);

            if ($metadata['type'] != 'file') continue;

            $uuid = json_encode($metadata);

            if (isset(self::$_metadataCache[$uuid]))
            {
                $itemList[] = self::$_metadataCache[$uuid];
            }
            else
            {
                $itemList[] = self::$_metadataCache[$uuid] = new StorageItem($metadata);
            }
        }

        $collection = new StorageItemCollection($itemList);

        $collection->setCachedFilesystemAdapter(self::$cachedFileSystemAdapter);
        
        return $collection;
    }

    /**
     * @return array
     */
    public function getItemPathList() : Array
    {
        return $this->_itemPathList;
    }

    /**
     * @return FilesystemResolverFactory
     */
    public function getFileSystemResolverFactory() : FilesystemResolverFactory
    {
        return $this->_fileSystemResolverFactory;
    }

    /**
     * @return string
     */
    public function getRepositoryClassName() : string
    {
        return $this->_repositoryClassName;
    }

    /**
     * @return string
     */
    public function getUuid() : string
    {
        return $this->_uuid;
    }

    public function getCachedFilesystemAdapter()
    {
        return self::$cachedFileSystemAdapter;
    }
}