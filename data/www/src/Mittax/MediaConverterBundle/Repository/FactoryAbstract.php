<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 15.12.16
 * Time: 23:44
 */

namespace Mittax\MediaConverterBundle\Repository;

use Mittax\MediaConverterBundle\Exception\RepositoryClassNotFoundException;

/**
 * Class Factory
 * @package Mittax\MediaConverterBundle\Repository
 */
abstract class FactoryAbstract implements IRepositoryFactory
{
    /**
     * @var IRepository[]
     */
    protected static $_repositories;

    /**
     * @var IRepositoryConfiguration
     */
    private $repositoryConfiguration;

    /**
     * Factory constructor.
     * @param IRepositoryConfiguration $repositoryConfiguration
     */
    public function __construct(IRepositoryConfiguration $repositoryConfiguration = null)
    {
        $this->repositoryConfiguration = $repositoryConfiguration;

        if(!isset(self::$_repositories[$repositoryConfiguration->getUuid()]))
        {
            $this->build($repositoryConfiguration);
        }
    }

    /**
     * @param IRepositoryConfiguration $repositoryConfiguration
     * @return bool
     */
    public function build(IRepositoryConfiguration $repositoryConfiguration) : bool
    {
        $repositoryClassName = $repositoryConfiguration->getRepositoryClassName();

        if (!class_exists($repositoryClassName))
        {
            throw new RepositoryClassNotFoundException('Repositoryclass not found: ' . $repositoryClassName);
        }

        /** @var  $repository  IRepository*/
        $repository = new $repositoryClassName($repositoryConfiguration);

        self::$_repositories[$repositoryConfiguration->getUuid()] = $repository;

        return true;
    }

    /**
     * @return IRepository[]
     */
    public static function getRepositories() : Array
    {
        return self::$_repositories;
    }

    /**
     * @param string $uuid
     * @return IRepository
     */
    public function getByUuid(string $uuid)
    {
        if ( !isset( self::$_repositories[$uuid] ) )
        {
            throw new RepositoryClassNotFoundException('Repository with uuid not found: ' . $uuid);
        }

        return self::$_repositories[$uuid];
    }

    /**
     * @return IRepositoryConfiguration
     */
    public function getRepositoryConfiguration()
    {
        return $this->repositoryConfiguration;
    }
}