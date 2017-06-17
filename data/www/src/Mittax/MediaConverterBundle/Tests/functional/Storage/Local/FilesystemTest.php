<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 23:25
 */

namespace Mittax\MediaConverterBundle\Tests\Storage\Local;

use League\Flysystem\Config;
use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem as LocalFileSystem;
use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class LocalFilesysystemTest extends AbstractKernelTestCase
{

    /**
     * @var LocalFileSystem
     */
    private $_fileSystemWrapper;

    /**
     * @var IAdapter
     */
    private $_cachedFilesystemAdapter;

    public function setUp()
    {
        parent::setUp();

        $this->_fileSystemWrapper = new LocalFileSystem($this->_storagePath);

        $this->_cachedFilesystemAdapter = LocalFileSystem::getCachedAdapter($this->_storagePath);

    }

    public function testInstances()
    {
        $this->_cachedFilesystemAdapter = LocalFileSystem::getCachedAdapter($this->_storagePath);

        $this->assertNotNull($this->_cachedFilesystemAdapter);
    }

    public function testInstanceOfFileSystemWrapper()
    {
        $this->assertInstanceOf(LocalFileSystem::class, $this->_fileSystemWrapper);
    }

    public function testInstanceCachedFilesystemAdapter()
    {
        $this->assertInstanceOf(IAdapter::class, $this->_cachedFilesystemAdapter);
    }

    public function testListContentsOnFlySystemAdapter()
    {
        $this->assertGreaterThan(0, count($this->_cachedFilesystemAdapter->listContents('assets', true)));
    }
    
    public function testConvertStoragePathToUrl()
    {
        $toReplace = "export/7ab19f1c56e9c652157a0029396f3281/SampleVideo_1280x720_1mb_200x200_lowres.mp4";

        $url = Filesystem::convertStoragePathToUrl($toReplace);

        $this->assertTrue(strpos($url,'://') >-1);

        $this->assertTrue(strpos($url,'export') >-1);
    }

    public function testGetUuidFromPath()
    {
        $toReplace = "export/7ab19f1c56e9c652157a0029396f3281/SampleVideo_1280x720_1mb_200x200_lowres.mp4";

        $uuid = Filesystem::getUuidFromPath($toReplace);

        $this->assertEquals("7ab19f1c56e9c652157a0029396f3281", $uuid);
    }
    
    public function testImportPathFromUploadFolder()
    {
        $cachedAdapter = Filesystem::getCachedAdapter("storage/upload");

        $cachedAdapter->createDir("atestfolder" , new Config());

        $this->assertTrue($cachedAdapter->has("atestfolder"));

        /**
         * Write a file in created folder
         */
        $cachedAdapter = Filesystem::getCachedAdapter("storage");

        $path = "upload/atestfolder/atestfile.txt";

        $cachedAdapter->write($path, "textcontent", new Config());

        $this->assertTrue($cachedAdapter->has($path));

        /**
         * Import file
         */
        /** @var  $localFileSystemService Filesystem*/

        $expected= \Mittax\MediaConverterBundle\Service\System\Config::getStoragePath() . "/" . "assets/atestfolder/atestfile.txt";

        $localFileSystemService = $this->container->get('mittax.mediaconverterbundle.service.storage.local.filesystem');

        $localFileSystemService->importPathFromUploadFolder($path);

        $targetFile = $expected;

        $this->assertTrue(file_exists($targetFile));

        /**
         * Delete testfiles
         */
        unlink($expected);

        rmdir(\Mittax\MediaConverterBundle\Service\System\Config::getStoragePath() . "/assets/atestfolder");
    }
}