<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 21.12.16
 * Time: 23:50
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat;


/**
 * Class Jpg
 * @package Mittax\MediaConverterBundle\Tests\ValueObjects\ThumbnailConfig
 */
class Jpg extends OutputFormatAbstract
{
    /**
     * @var string
     */
    private $_format = '';

    /**
     * @var array
     */
    private $_quality;
}