<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:09
 */

namespace Mittax\MediaConverterBundle\Service\Uuid;

use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;

/**
 * Class StorageItemRepository
 * @package Mittax\MediaConverterBundle\Service\Uuid
 */
class StorageItemRepositoryConfig
{
    /**
     * @var StorageRepositoryConfig
     */
    private $storageRepositoryConfig;

    /**
     * StorageItemRepository constructor.
     * @param StorageRepositoryConfig $storageRepositoryConfig
     */
    public function __construct(StorageRepositoryConfig $storageRepositoryConfig)
    {
        $this->storageRepositoryConfig = $storageRepositoryConfig;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return md5(json_encode($this->storageRepositoryConfig->getItemPathList()));
    }
}