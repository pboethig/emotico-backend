<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.06.17
 * Time: 17:11
 */

namespace Mittax\MediaConverterBundle\Service\Converter\Cropping;


use Mittax\MediaConverterBundle\ValueObjects\BrowserImageData;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;
use Symfony\Component\Process\Process;

/**
 * Class Cropp
 * @package Mittax\MediaConverterBundle\Service\Converter\Cropping
 */
class Crop
{
    /**
     * @param string $sourceImagePath
     * @param string $targetPath
     * @param CroppingData $croppingData
     * @param BrowserImageData $browserImageData
     * @return string
     */
    public function crop(string $sourceImagePath, string $targetPath, CroppingData $croppingData, BrowserImageData $browserImageData)
    {
        $scaledCroppingData = Scaling::getHiresCroppingData($croppingData, $browserImageData, $sourceImagePath);

        $command = 'convert "' . $sourceImagePath
            . '" -flatten  -crop '
            . $scaledCroppingData->getWidth()
            . 'x' . $scaledCroppingData->getHeight()
            . '+' . $scaledCroppingData->getTop()
            . '+' . $scaledCroppingData->getLeft()
            . ' "' . $targetPath . '"';

        $process = new Process($command);

        $process->run();

        return $command;
    }
}