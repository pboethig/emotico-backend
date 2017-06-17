<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 15.12.16
 * Time: 23:45
 */

namespace Mittax\MediaConverterBundle\Repository;


interface IRepositoryFactory
{
    /**
     * @return IRepository[]
     */
    public static function getRepositories() : Array;
}