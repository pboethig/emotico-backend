<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 22.12.16
 * Time: 00:13
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat;
use Mittax\MediaConverterBundle\ValueObjects\Size;

/**
 * Interface IOutputFormat
 * @package Mittax\MediaConverterBundle\Tests\ValueObjects\ThumbnailConfig
 */
interface IOutputFormat
{
    /**
     * @return string
     */
    public function getFormat(): string;

    /**
     * @return array
     */
    public function getQuality(): Array;

    /**
     * @return Size[]
     */
    public function getSizes() : Array;

    /**
     * @return string
     */
    public function getMode() : string;

    /**
     * @return string
     */
    public function getFilter() : string;

    /**
     * @return string
     */
    public function getAdditionalFilter() : string ;
}