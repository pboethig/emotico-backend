<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 04.12.16
 * Time: 17:57
 */

namespace Mittax\RabbitMQBundle\Traits\Creation;


/**
 * Class Construct
 * @package Mittax\RabbitMQBundle\Traits\Creation
 */
trait Construct
{
    /**
     * Builds Object from array
     *
     * @param array $keyValue
     * @param string $exceptionClassName
     * @return bool
     */
    public function constructByKeyValue(Array $keyValue, string $exceptionClassName = '') : bool
    {
        foreach ($keyValue as $propertyName => $value)
        {
            $_propertyName = '_' .$propertyName;

            if (!empty($exceptionClassName))
            {
                if (!property_exists($this, $_propertyName))
                {
                    throw new \InvalidArgumentException('Property: '. $propertyName . ' does not exits in: ' . get_class($this));
                }

                $this->{$_propertyName} = $value;
            }
        }

        return true;
    }
}