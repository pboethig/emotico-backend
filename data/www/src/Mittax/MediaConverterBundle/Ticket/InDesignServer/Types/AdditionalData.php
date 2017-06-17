<?php

/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 24.05.17
 * Time: 19:34
 */
namespace Mittax\MediaConverterBundle\Ticket\InDesignServer\Types;

/**
 * Class AdditionalData
 * @package Mittax\MediaConverterBundle\Ticket\InDesignServer\Types
 */
class AdditionalData
{
    /**
     * @var string
     */
    public $classname ="";

    /**
     * @var string
     */
    public $property ="";

    /**
     * AdditionalData constructor.
     * @param string $className
     * @param string $property
     */
    public function __construct(string $className, string $property)
    {
        $this->property = $property;

        $this->classname = $className;
    }
}