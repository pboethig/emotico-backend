<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 16:53
 */

namespace Mittax\MediaConverterBundle\Service\Converter\Cropping;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket\Producer;
use Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket\Ticket;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;

/**
 * Class Facade
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
class Facade
{
    /**
     * @var string
     */
    private $storagePath;

    /**
     * @var CroppingData
     */
    private $croppingData;

    /**
     * Facade constructor.
     * @param string $storagePath
     * @param CroppingData $croppingData
     */
    public function __construct(string $storagePath, CroppingData $croppingData)
    {
        $this->storagePath = $storagePath;

        $this->croppingData = $croppingData;
    }

    /**
     * @return bool
     */
    public function produce() : bool
    {
        $imageMetadata = Filesystem::getCachedAdapter('storage')->getMetadata($this->storagePath);

        $storageItem = new StorageItem($imageMetadata);

        $ticket = new Ticket($storageItem, $this->croppingData);

        $producer = new Producer([$ticket]);

        return $producer->execute();
    }
}