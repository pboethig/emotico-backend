<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 14:48
 */

namespace Mittax\MediaConverterBundle\Repository;


interface IRepositoryConfiguration
{
    /**
     * @return string
     */
    public function getRepositoryClassName() : string;

    /**
     * @return mixed
     */
    public function getUuid() : string;

    /**
     * @return callable
     */
    public function buildCollection();

    /**
     * @return mixed
     */
    public function validate() : bool;
}