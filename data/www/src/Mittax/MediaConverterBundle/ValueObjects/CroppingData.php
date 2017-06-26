<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 18:01
 */

namespace Mittax\MediaConverterBundle\ValueObjects;

/**
 * Class Version
 * @package Mittax\MediaConverterBundle\ValueObjects
 */
class CroppingData
{
    /**
     * @var float
     */
    private $width = 0.00;

    /**
     * @var float
     */
    private $height = 0.00;

    /**
     * @var float
     */
    private $top = 0.00;

    /**
     * @var float
     */
    private $left = 0.00;

    /**
     * @var string
     */
    private $messurement='px';

    /**
     * CroppingData constructor.
     */
    public function __construct(\stdClass $croppingData)
    {
        $this->width = $croppingData->width;

        $this->height = $croppingData->height;

        $this->top = $croppingData->top;

        $this->left = $croppingData->left;

        $this->messurement = $croppingData->messurement;
    }
}