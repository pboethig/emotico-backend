<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 29.12.16
 * Time: 22:29
 */

namespace Mittax\MediaConverterBundle\Tests\Event\Listener;

use Mittax\MediaConverterBundle\Event\Upload\BookPackageUploadFinished;
use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class FineDataCreated
 * @package Mittax\MediaConverterBundle\Event\Thumbnail
 */
class BookPackageUploadFinshedTest extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testimportBookPackagePathFromUploadFolder()
    {
        $packageFilename = "BookExportJPG.indb.zip";

        $packagePath = '/test/buch/'.$packageFilename;

        $folderName = md5(basename($packagePath));

        /**
         * Create uploadfolder and store testpackage
         */
        @mkdir(Config::getUploadPath()."/" . $folderName);

        $targetPath = $folderName."/".$packageFilename;

        copy(Config::getStoragePath() . $packagePath,Config::getUploadPath(). "/" . $targetPath);

        $this->assertTrue(file_exists(Config::getUploadPath(). "/" . $targetPath));

        /**
         * Dispatch event
         */
        $event = new BookPackageUploadFinished($packagePath);

        $dispatcher = new EventDispatcher();

        $listener = new \Mittax\MediaConverterBundle\Event\Listener\Upload\BookPackageUploadFinished($this->container);

        $dispatcher->addListener($event::NAME, array($listener, 'onBookPackageUploadFinished'));

        $event=$dispatcher->dispatch($event::NAME, $event);

        $this->assertNotEmpty($event->getFilePath());

        $this->assertNotNull($event);
    }



    public function testdispatchPackageEventOnMatchingExtension()
    {
        $filename="BookExportJPG.indb.zip";

        $filePath="test/buch/BookExportJPG.indb.zip";

        $upload = new Upload($this->container);

        $res=$upload->dispatchPackageEventOnMatchingExtension($filename, $filePath);

        $this->assertTrue($res);
    }

}