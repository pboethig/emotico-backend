<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 17:57
 */

namespace Mittax\MediaConverterBundle\Traits\Creation;


/**
 * Class Construct
 * @package Mittax\MediaConverterBundle\Traits\Creation
 */
trait Construct
{
    /**
     * @param array $keyValue
     * @return bool
     */
    public function constructByKeyValue(Array $keyValue) : bool
    {
        foreach ($keyValue as $propertyName => $value)
        {
            $_propertyName = '_' .$propertyName;

            $this->{$_propertyName} = $value;
        }

        return true;
    }
}