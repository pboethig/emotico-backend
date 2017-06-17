<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:44
 */

namespace Mittax\MediaConverterBundle\Service\Uuid;

use Ramsey\Uuid\Uuid;

class Generator
{
    /**
     * @return string
     */
    public static function generate()
    {
        $uuid4 = Uuid::uuid4();

        return $uuid4->toString();
    }
}