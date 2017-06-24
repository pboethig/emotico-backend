<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 03.01.17
 * Time: 00:38
 */

namespace Mittax\MediaConverterBundle\Repository\Converter\Thumbnail;

use Imagine\Image\Box;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\Thumbnail\JobTicketFineDataCreated;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\IOutputFormat;
use Mittax\MediaConverterBundle\Service\Format\SupportedFormatsBuilder;
use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Ticket\ITicket;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Ticket\Thumbnail\IThumbnailTicket;
use Mittax\MediaConverterBundle\Ticket\TicketAbstract;
use Mittax\MediaConverterBundle\ValueObjects\Format;
use Mittax\MediaConverterBundle\ValueObjects\Size;

/**
 * Class ThumbnailTicketBuilderAbstract
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
abstract class ThumbnailTicketBuilderAbstract extends TicketAbstract implements ITicketBuilder
{
    /**
     * @var Thumbnail[]
     */
    protected $_generatedThumbnails = [];

    /**
     * @var string
     */
    protected $_currentTargetStoragePath;

    /**
     * @var IConverter
     */
    protected $_converter;

    /**
     * @var IOutputFormat
     */
    protected $_currentOutputFormat;

    /**
     * @var StorageItem
     */
    protected $_storageItem;

    /**
     * @var Size
     */
    protected $_currentSize;

    /**
     * @var string
     */
    protected $_currentTempFilePath;

    /**
     * @var string
     */
    protected $_ticketClassName = '';

    /**
     * @var string
     */
    protected $_boxClassName = '\Mittax\MediaConverterBundle\ValueObjects\Box';

    /**
     * @var string
     */
    protected $_currentMode;

    /**
     * @var Box
     */
    protected $_currentBox;

    /**
     * @var IThumbnailTicket
     */
    protected $_jobTicket;

    /**
     * Implement a returntyped getJobTicket to get specific properties
     *
     * @return ITicket
     */
    abstract public function getJobTicket();

    /**
     * Builder constructor.
     * @param StorageItem $storageItem
     */
    public function __construct(StorageItem $storageItem)
    {
        $this->init($storageItem);

        $this->build();
    }

    /**
     * @param StorageItem $storageItem
     * @return bool
     */
    public function init(StorageItem $storageItem) : bool
    {
        $this->_converter = $this->buildConverter($storageItem);

        $this->_storageItem = $storageItem;

        return true;
    }

    /**
     * @param StorageItem $storageItem
     * @return IConverter
     */
    public function buildConverter(StorageItem $storageItem)
    {
        $converterService = new SupportedFormatsBuilder();

        $format = new Format(['name'=>$storageItem->getExtension()]);

        $converter = $converterService->getConverterByFormat($format);

        return $converter;
    }

    /**
     * Iterates all formats defines in systemconfig
     * @see mediaconverter.yml
     *
     * @return bool
     */
    public function build() : bool
    {
        $this->processOutputFormats();

        return true;
    }

    protected function processOutputFormats()
    {
        foreach ($this->_converter->getConverterConfig()->getThumbnailOutputFormats() as $this->_currentOutputFormat)
        {
            if ($this->_currentOutputFormat->getMode())
            {
                $this->_currentMode = $this->_currentOutputFormat->getMode();
            }

            $this->_processSizes($this->_currentOutputFormat->getSizes());
        }
    }

    /**
     * Iterates all configured sizes of thumbnails.
     * @see mediaconverter.yml
     *
     * @param Size[] $sizes
     * @return bool
     */
    protected function _processSizes(Array $sizes) : bool
    {
        foreach ($sizes as $this->_currentSize)
        {
            $this->_buildTargetPaths();

            $this->_currentBox = new $this->_boxClassName($this->_currentSize->getWidth(), $this->_currentSize->getHeight());

            $this->_buildJobTicket();

            $thumbnail = new Thumbnail($this->buildThumbnailDataArray());

            $this->_generatedThumbnails[$thumbnail->getUuid()] = $thumbnail;
        }

        return true;
    }

    /**
     * Builds temp and target path
     *
     * @return bool
     */
    protected function _buildTargetPaths() : bool
    {
        $this->_currentTempFilePath = $this->_buildTempFilePath();

        $this->_currentTargetStoragePath = $this->_buildTargetStoragePath();

        return true;
    }

    protected function _buildJobTicket()
    {
        $this->_jobTicket = new $this->_ticketClassName($this);

        $this->_converter->attachJobTicket($this->_jobTicket);

        $this->dispatchEvent($this->_jobTicket);
    }


    /**
     * @param IThumbnailTicket $jobTicket
     */
    private function dispatchEvent(IThumbnailTicket $jobTicket)
    {
        Dispatcher::getInstance()->dispatch(JobTicketFineDataCreated::NAME, new JobTicketFineDataCreated($jobTicket));
    }

    /**
     * @return array
     */
    protected function buildThumbnailDataArray() : Array
    {
        return [
            'sourcePath' => $this->_storageItem->getPath(),
            'targetPath' => $this->_currentTempFilePath,
            'size' =>  0,
            'width' => $this->_currentSize->getWidth(),
            'height' => $this->_currentSize->getHeight(),
            'rawData' => $this->_storageItem->getRawData()
        ];
    }

    /**
     * Build target storage from tempfolder
     * @todo generate from original storagepath
     *
     * @return string
     */
    protected function _buildTargetStoragePath() : string
    {
        $targetFolder = Upload::md5($this->_storageItem->getFilename());

        if(!is_dir($targetFolder))
        {
            $exportFolder = \Mittax\MediaConverterBundle\Service\System\Config::getExportPath(). '/' . $targetFolder ;

            if(!is_dir($exportFolder))
            {
                mkdir($exportFolder);
                chmod($exportFolder, 0777);
            }
            else
            {
                chmod($exportFolder, 0777);
            }
        }

        $path = str_replace('temp/','export/' . $this->_storageItem->getUuid() . '/', $this->_currentTempFilePath);

        $path = str_replace('storage/','', $path);

        return $path .'.'. $this->getCurrentOutputFormat()->getFormat();
    }

    /**
     * Returns local temporary path
     *
     * @return string
     */
    protected function _buildTempFilePath() : string
    {
        return 'temp/'

        . $this->_storageItem->getFilename()

        . '_'

        . $this->_currentSize->getWidth()

        . 'x'

        . $this->_currentSize->getHeight();
    }

    /**
     * Returns generates thumbnail objects
     *
     * @return \Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail[]
     */
    public function getGeneratedThumbnails() : Array
    {
        return $this->_generatedThumbnails;
    }

    /**
     * @return ITicket[]
     */
    public function getJobTickets() : Array
    {
        return $this->_converter->getJobTickets();
    }

    /**
     * @return mixed
     */
    public function getCurrentTargetStoragePath()
    {
        return $this->_currentTargetStoragePath;
    }

    /**
     * @return string
     */
    public function getCurrentTempFilePath()
    {
        return $this->_currentTempFilePath;
    }

    /**
     * @return Size
     */
    public function getCurrentSize()
    {
        return $this->_currentSize;
    }

    /**
     * @return StorageItem
     */
    public function getStorageItem()
    {
        return $this->_storageItem;
    }

    /**
     * @return IOutputFormat
     */
    public function getCurrentOutputFormat()
    {
        return $this->_currentOutputFormat;
    }

    /**
     * @return IConverter
     */
    public function getConverter()
    {
        return $this->_converter;
    }

    /**
     * @return string
     */
    public function getCurrentMode()
    {
        return $this->_currentMode;
    }

    /**
     * @return Box
     */
    public function getCurrentBox()
    {
        return $this->_currentBox;
    }
}