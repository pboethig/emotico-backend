<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Event\Upload;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class CollectionCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class BookPackageUploadFinished extends Event
{
    const NAME = 'book.package.upload.finished';

    /**
     * @var string
     */
    private $filePath="";

    /**
     * AssetUploadFinished constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
}