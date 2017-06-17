<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 15.12.16
 * Time: 23:44
 */

namespace Mittax\MediaConverterBundle\Repository\Metadata;
use Mittax\MediaConverterBundle\Repository\FactoryAbstract;

/**
 * Class Factory
 * @package Mittax\MediaConverterBundle\Repository
 */
class Factory extends FactoryAbstract
{
    /**
     * @param string $uuid
     * @return ItemRepository
     */
    public function getByUuid(string $uuid) : ItemRepository
    {
        return parent::$_repositories[$uuid];
    }
}