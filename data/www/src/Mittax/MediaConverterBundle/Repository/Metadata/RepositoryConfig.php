<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 11:07
 */

namespace Mittax\MediaConverterBundle\Repository\Metadata;

use Mittax\MediaConverterBundle\Collection\Metadata;
use Mittax\MediaConverterBundle\Repository\IRepositoryConfiguration;

/**
 * Class StorageRepositoryConfig
 * @package Mittax\MediaConverterBundle\Repository\Storage
 */
class RepositoryConfig implements IRepositoryConfiguration
{
    /**
     * @var string
     */
    private $_repositoryClassName = ItemRepository::class;

    /**
     * @var Metadata
     */
    private $_metadataCollection;

    /**
     * @var string
     */
    private $_uuid;

    /**
     * @var int
     */
    private $_timeStamp;

    /**
     * RepositoryConfig constructor.
     * @param Metadata $metadataCollection
     */
    public function __construct(Metadata $metadataCollection)
    {
        $this->_metadataCollection = $metadataCollection;

        $this->validate();

        $this->_timeStamp = time();

        $this->_uuid = $this->buildUuid($this);
    }

    /**
     * @param RepositoryConfig $repositoryConfig
     * @return string
     */
    public function buildUuid(RepositoryConfig $repositoryConfig) : string
    {
        return uniqid();
    }

    /**
     * @return bool
     */
    public function validate() : bool
    {
        return true;
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

    /**
     * @return Metadata
     */
    public function buildCollection()
    {
        return $this->_metadataCollection;
    }
}