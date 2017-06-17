<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 17:57
 */

namespace Mittax\MediaConverterBundle\ValueObjects;

use Mittax\MediaConverterBundle\Exception\InvalidConverterConfigException;
use Mittax\MediaConverterBundle\Exception\OutputFormatNotExistException;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\IOutputFormat;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;
use Mittax\MediaConverterBundle\Service\Format\SupportedFormatsBuilder;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Traits\Creation\Construct;

/**
 * Class ConverterConfig
 * @package Mittax\MediaConverterBundle\ValueObjects
 */
class ConverterConfig
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var Version
     */
    private $_version;

    /**
     * @var string
     */
    private $_thumbnailConverterClassName;

    /**
     * @var string
     */
    private $_producerClassName = '';

    /**
     * @var \Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\IOutputFormat[]
     */
    private $_thumbnailOutputFormats;

    /**
     * @var string
     */
    private $_executable;

    /**
     * @var Format[]
     */
    private $_formats;

    /**
     * @var SupportedFormatsBuilder
     */
    private $_formatListBuilderService;

    /**
     * @var StorageRepositoryConfig
     */
    private $_storageRepositoryConfig;

    /**
     * @var string
     */
    private $_tempFolderPath;

    use Construct;

    /**
     * ConverterConfig constructor.
     * @param array $rawData
     */
    public function __construct(Array $rawData)
    {
        $this->constructByKeyValue($rawData);

        $this->validate($rawData);

        $this->setFormatListBuilderService(new SupportedFormatsBuilder());

        $this->setVersion(new Version($rawData['version']));

        $this->setFormats($this->_formatListBuilderService->getByConverterName($this->_name));

        $this->setThumbnailOutputFormats($this->buildOutputFormats($rawData));

        $this->setTempFolderPath(Config::getPaths()['temp']);
    }

    /**
     * @return string
     */
    public function getProducerClassName()
    {
        return $this->_producerClassName;
    }

    /**
     * @return string
     */
    public function getTempFolderPath()
    {
        return $this->_tempFolderPath;
    }

    /**
     * @param string $tempFolderPath
     */
    public function setTempFolderPath($tempFolderPath)
    {
        $this->_tempFolderPath = $tempFolderPath;
    }

    /**
     * @param array $thumbnailOutputFormats
     */
    public function setThumbnailOutputFormats(Array $thumbnailOutputFormats)
    {
        $this->_thumbnailOutputFormats = $thumbnailOutputFormats;
    }

    /**
     * @param SupportedFormatsBuilder $formatListBuilderService
     */
    public function setFormatListBuilderService($formatListBuilderService)
    {
        $this->_formatListBuilderService = $formatListBuilderService;
    }

    /**
     * @param Version $version
     */
    public function setVersion($version)
    {
        $this->_version = $version;
    }

    /**
     * @param Format[] $formats
     */
    public function setFormats($formats)
    {
        $this->_formats = $formats;
    }

    /**
     * @param array $rawData
     * @return IOutputFormat[]
     */
    public function buildOutputFormats(Array $rawData) : Array
    {
        $outputFormats = [];

        foreach ($rawData['thumbnailOutputFormat'] as $outputFormatConfigItem)
        {
            $outputFormatClassName = 'Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\\' . ucfirst($outputFormatConfigItem['format']);

            if (!class_exists($outputFormatClassName))
            {
                throw new OutputFormatNotExistException('Class does not exist: ' . $outputFormatClassName);
            }

            $outputFormats[] = new $outputFormatClassName($outputFormatConfigItem);
        }

        return $outputFormats;
    }

    /**
     * @param StorageRepositoryConfig $storageRepositoryConfig
     */
    public function setStorageRepositoryConfig(StorageRepositoryConfig $storageRepositoryConfig)
    {
        $this->_storageRepositoryConfig = $storageRepositoryConfig;
    }

    /**
     * @return StorageRepositoryConfig
     */
    public function getStorageRepositoryConfig()
    {
        return $this->_storageRepositoryConfig;
    }

    /**
     * @param array $rawConfig
     * @return bool
     */
    public function validate(Array $rawConfig) : bool
    {
        if (!isset($rawConfig['name']))
        {
            throw new InvalidConverterConfigException('Convertername not specified');
        }

        return true;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->_name;
    }

    /**
     * @return Version
     */
    public function getVersion() : Version
    {
        return $this->_version;
    }

    /**
     * @return string
     */
    public function getThumbnailConverterClassName() : string
    {
        return $this->_thumbnailConverterClassName;
    }

    /**
     * @return array
     */
    public function getExecutable() : Array
    {
        return $this->_executable;
    }

    /**
     * @return Format[]
     */
    public function getFormats() : Array
    {
        return $this->_formats;
    }

    /**
     * @return SupportedFormatsBuilder
     */
    public function getFormatListBuilderService() : SupportedFormatsBuilder
    {
        return $this->_formatListBuilderService;
    }

    /**
     * @return \Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\IOutputFormat[]
     */
    public function getThumbnailOutputFormats() : Array
    {
        return $this->_thumbnailOutputFormats;
    }
}