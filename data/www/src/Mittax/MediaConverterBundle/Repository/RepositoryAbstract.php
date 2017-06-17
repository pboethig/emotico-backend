<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 15:20
 */

namespace Mittax\MediaConverterBundle\Repository;

use Mittax\ObjectCollection\ICollection;

abstract class RepositoryAbstract implements IRepository
{
    /**
     * @param IRepositoryConfiguration $config
     * @return ICollection
     */
    public function buildCollection(IRepositoryConfiguration $config)
    {
        return $config->buildCollection();
    }
}