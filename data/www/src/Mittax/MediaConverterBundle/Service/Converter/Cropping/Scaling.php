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

        $scalingFactorWidth =  $origialWidth / $browserImageData->getWidth();

        $scalingFactorHeight =  $originalHeight / $browserImageData->getHeight();

        echo "\n------------BIW---------------\n";
        var_dump($browserImageData->getWidth());

        echo "\n------------OW---------------\n";
        var_dump($origialWidth);

        echo "\n------------OH---------------\n";
        var_dump($originalHeight);


        echo "\n------------SW---------------\n";
        var_dump($scalingFactorWidth);

        echo "\n------------SH---------------\n";
        var_dump($scalingFactorHeight);

        $scaledCropping = new \stdClass();

        echo "\n------------BW---------------\n";
        var_dump($browserCroppingData->getWidth());

        echo "\n------------BH---------------\n";
        var_dump($browserCroppingData->getHeight());

        echo "\n---------------------------\n";


        $scaledCropping->width  = (int)($browserCroppingData->getWidth()  * $scalingFactorWidth);

        $scaledCropping->height = (int)($browserCroppingData->getHeight() * $scalingFactorHeight);

        $scaledCropping->top    = (int)($browserCroppingData->getTop() * $scalingFactorHeight);

        $scaledCropping->left   = (int)($browserCroppingData->getLeft() * $scalingFactorWidth);

        return new CroppingData($scaledCropping);
    }
}