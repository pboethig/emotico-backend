<?php

namespace Mittax\MediaConverterBundle\Tests\Controller;

use Mittax\MediaConverterBundle\Service\System\Config;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetControllerTest extends WebTestCase
{
    public function testUpload()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/assets/upload');

        $this->assertNotNull($crawler);
    }

    public function testDownloadHighresAction()
    {
        $client = static::createClient();

        /**
         * Create testfile
         */
        $testFolder = "assets/testfolder";

        @mkdir(Config::getStoragePath()."/".$testFolder);

        $testFile = $testFolder . "/testfile.txt";

        $this->assertEquals($testFile, "assets/testfolder/testfile.txt");

        file_put_contents(Config::getStoragePath() ."/". $testFile, "testcontent");

        /**
         * Make request
         */
        $path = base64_encode($testFile);

        $crawler = $client->request('GET', '/assets/'.$path.'/downloadHighres');

        $this->assertNotNull($crawler);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        /**
         * Delete file
         */
        @unlink(Config::getStoragePath() ."/". $testFile);

        @rmdir(Config::getStoragePath()."/".$testFolder);
    }

}
