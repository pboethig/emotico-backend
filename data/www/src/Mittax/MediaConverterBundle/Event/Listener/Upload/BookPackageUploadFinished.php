<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 30.12.16
 * Time: 20:33
 */

namespace Mittax\MediaConverterBundle\Event\Listener\Upload;

use Mittax\MediaConverterBundle\Service\Converter\Thumbnail\Facade;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BookPackageUploadFinished
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ImagineRuntimeException constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Mittax\MediaConverterBundle\Event\Upload\BookPackageUploadFinished $event
     */
    public function onBookPackageUploadFinished(\Mittax\MediaConverterBundle\Event\Upload\BookPackageUploadFinished $event)
    {
        /**
         * Import Package from uploadfolder
         */
        /** @var  $fileSystemService Filesystem */
        $fileSystemService = new Filesystem();

        $bookPath = $fileSystemService->importBookPackagePathFromUploadFolder($event->getFilePath());

        /**
         * Generate thumbnails
         */
        $thumbnailFacade = new Facade($this->container);

        $thumbnailFacade->generate([$bookPath]);
    }
}