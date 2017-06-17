<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 19:25
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail;
use Mittax\MediaConverterBundle\Repository\FactoryAbstract;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;

/**
 * Class Factory
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
class Factory extends FactoryAbstract
{

    /**
     * Factory constructor.
     * @param StorageRepositoryConfig $storageRepositoryConfig
     */
    public function __construct(StorageRepositoryConfig $storageRepositoryConfig)
    {
        $converterFactoryConfig = new RepositoryConfig($storageRepositoryConfig);

        parent::__construct($converterFactoryConfig);
    }


    /**
     * @param string $uuid
     * @return ItemRepository
     */
    public function getByUuid(string $uuid) : ItemRepository
    {
        return parent::$_repositories[$uuid];
    }
}