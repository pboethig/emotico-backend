<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 16:53
 */

namespace Mittax\MediaConverterBundle\Service\Converter\Cropping;

use Mittax\MediaConverterBundle\ValueObjects\BrowserImageData;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;

/**
 * Class Scaling
 * @package Mittax\MediaConverterBundle\Service\Converter\Cropping
 */
class Scaling
{

    /**
     * Scales up the webcroppingData on highres assset cropping data
     *
     * @param CroppingData $browserCroppingData
     * @param BrowserImageData $browserImageData
     * @param string $storagePath
     * @return CroppingData
     */
    public static function getHiresCroppingData(CroppingData $browserCroppingData, BrowserImageData $browserImageData, string $storagePath) : CroppingData
    {
        list($origialWidth, $originalHeight) = getimagesize($storagePath);

        $scalingFactorWidth =  $origialWidth / $browserImageData->getNaturalWidth();

        $scalingFactorHeight =  $originalHeight / $browserImageData->getNaturalHeight();

        $scaledCropping = new \stdClass();

        $scaledCropping->width  = ($browserCroppingData->getWidth()  * $scalingFactorWidth);

        $scaledCropping->height = ($browserCroppingData->getHeight() * $scalingFactorHeight);

        $scaledCropping->top    = ($browserCroppingData->getTop() * $scalingFactorHeight);

        $scaledCropping->left   = ($browserCroppingData->getLeft() * $scalingFactorWidth);

        return new CroppingData($scaledCropping);
    }
}