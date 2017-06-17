<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:09
 */

namespace Mittax\MediaConverterBundle\Service\Uuid;

use Mittax\MediaConverterBundle\Repository\Format\RepositoryConfig;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;

/**
 * Class FormatItemRepositoryConfig
 * @package Mittax\MediaConverterBundle\Service\Uuid
 */
class FormatItemRepositoryConfig
{
    /**
     * @var StorageRepositoryConfig
     */
    private $repositoryConfig;

    /**
     * StorageItemRepositoryConfig constructor.
     * @param RepositoryConfig $storageRepositoryConfig
     */
    public function __construct(RepositoryConfig $storageRepositoryConfig)
    {
        $this->repositoryConfig = $storageRepositoryConfig;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return spl_object_hash($this->repositoryConfig);
    }
}