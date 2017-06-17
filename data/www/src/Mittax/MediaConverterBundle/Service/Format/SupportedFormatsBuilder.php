<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 17:23
 */

namespace Mittax\MediaConverterBundle\Service\Format;

use Mittax\MediaConverterBundle\Exception\NoConverterForFormatFoundException;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\IConverter;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\ValueObjects\ConverterConfig;
use Mittax\MediaConverterBundle\ValueObjects\Format;
use Mittax\MediaConverterBundle\ValueObjects\FormatToMimeTypeList;

/**
 * Class ListBuilder
 * @package Mittax\MediaConverterBundle\Service\Format
 */
class SupportedFormatsBuilder
{
    /**
     * @var array
     */
    private $_converters;

    /**
     * @var FormatToMimeTypeList
     */
    private $_extensionToMimeTypeMap;

    /**
     * @var array
     */
    private static $formatListByConverter = [];

    /**
     * @var array
     */
    private static $rawFormatList = [];

    /**
     * @var array
     */
    private $_formatListGroupedByConverter;



    public function __construct()
    {
        $this->_extensionToMimeTypeMap = new FormatToMimeTypeList();

        $this->build();
    }

    /**
     * @return array
     */
    public function build() : Array
    {
        foreach (Config::getConverters() as $converterData)
        {
            $this->_converters[$converterData['name']] = $converterData;

            $this->_formatListGroupedByConverter[$converterData['name']] = $this->getByConverterName($converterData['name']);
        }

        return $this->_formatListGroupedByConverter;
    }

    /**
     * @param string $converterName
     * @return array
     */
    public function getFormatListByConverterName(string $converterName) : Array
    {
        if (isset(self::$rawFormatList[$converterName]))
        {
            self::$rawFormatList[$converterName];
        }

        $formatString = $this->_converters[$converterName]['formats'];

        self::$rawFormatList[$converterName] = explode(',', $formatString);

        return self::$rawFormatList[$converterName];
    }

    /**
     * @param Format $format
     * @return IConverter
     */
    public function getConverterByFormat(Format $format)
    {
        foreach ($this->_formatListGroupedByConverter as $converterName => $_formatList)
        {
             /** @var  $_format Format*/
            foreach ($_formatList as $_format)
            {
                if (strtolower($format->getName()) == strtolower($_format->getName()))
                {
                    return $this->getConverterByName($converterName);
                }
            }
        }

        throw new NoConverterForFormatFoundException('no converter found for this format: ' . $format->getName());
    }

    /**
     * @param string $converterName
     * @return IConverter
     */
    public function getConverterByName(string $converterName) : IConverter
    {
        if(!isset(Config::getConverters()[$converterName]))
        {
            throw new \InvalidArgumentException("Converter not implemented:" . $converterName);
        }

        $converter = Config::getConverters()[$converterName];

        $converterConfig = new ConverterConfig($converter);

        $converterClassName = $converterConfig->getThumbnailConverterClassName();

        return new $converterClassName($converterConfig);
    }

    /**
     * @param string $converterName
     * @return Format[]
     */
    public function getByConverterName(string $converterName) : Array
    {
        if (isset(self::$formatListByConverter[$converterName]))
        {
            return self::$formatListByConverter[$converterName];
        }

        $formatList = $this->getFormatListByConverterName($converterName);

        foreach ($formatList as $formatName)
        {
            self::$formatListByConverter[$converterName][] = $this->generateFormatByName($formatName);
        }

        return self::$formatListByConverter[$converterName];
    }

    /**
     * @param string $formatName
     * @return Format
     */
    public function generateFormatByName(string $formatName) : Format
    {
        $rawData = [
            'name' => $formatName,
            'type' => $formatName,
            'mimeType' => $this->_extensionToMimeTypeMap->getByExtension(strtolower($formatName)),
            'extension' => strtolower($formatName)
        ];

        return new Format($rawData);
    }
}