<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 05.01.17
 * Time: 14:04
 */

namespace Mittax\MediaConverterBundle\Ticket\Thumbnail;


use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Ticket\ITicketBuilder;
use Mittax\MediaConverterBundle\Ticket\TicketAbstract;
use Imagine\Image\Box;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\OutputFormat\IOutputFormat;
use Mittax\MediaConverterBundle\ValueObjects\Size;

class ThumbnailTicketAbstract extends TicketAbstract implements IThumbnailTicket
{

    /**
     * @var IOutputFormat
     */
    protected $currentOutputFormat;

    /**
     * @var \Mittax\MediaConverterBundle\Entity\Storage\StorageItem
     */
    protected $storageItem;

    /**
     * @var Size
     */
    protected $currentSize;

    /**
     * @var string
     */
    protected $currentTempFilePath;

    /**
     * @var Box
     */
    protected $currentBox;

    /**
     * @var string
     */
    protected $currentMode;

    /**
     * @var
     */
    protected $currentTargetStoragePath;

    /**
     * ThumbnailTicketAbstract constructor.
     * @param ITicketBuilder $builder
     */
    public function __construct(ITicketBuilder $builder)
    {
        $object = new \ReflectionClass($this);

        foreach ($object->getProperties() as $property)
        {
            $methodName = 'get' . ucfirst($property->getName());

            if (method_exists($builder, $methodName))
            {
                $this->{$property->getName()} = $builder->$methodName();
            }
        }

        parent::__construct($builder);

        $this->_jobId = Upload::md5($this->storageItem->getBasename());
    }

    /**
     * @return IOutputFormat
     */
    public function getCurrentOutputFormat()
    {
        return $this->currentOutputFormat;
    }

    /**
     * @return \Mittax\MediaConverterBundle\Entity\Storage\StorageItem
     */
    public function getStorageItem() : StorageItem
    {
        return $this->storageItem;
    }

    /**
     * @return Size
     */
    public function getCurrentSize() : Size
    {
        return $this->currentSize;
    }

    /**
     * @return string
     */
    public function getCurrentTempFilePath() : string
    {
        return $this->currentTempFilePath;
    }

    /**
     * @return Box
     */
    public function getCurrentBox()
    {
        return $this->currentBox;
    }

    /**
     * @return string
     */
    public function getCurrentMode() : string
    {
        return $this->currentMode;
    }

    /**
     * @return mixed
     */
    public function getCurrentTargetStoragePath() : string
    {
        return $this->currentTargetStoragePath;
    }

    /**
     * @param string $storagePath
     */
    public function setCurrentTargetStoragePath(string $storagePath)
    {
        $this->currentTargetStoragePath = $storagePath;
    }
}