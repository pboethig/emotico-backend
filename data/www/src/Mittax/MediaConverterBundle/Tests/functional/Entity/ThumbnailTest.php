<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 20:22
 */

namespace Mittax\MediaConverterBundle\Tests\Entity;

use Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class ThumbnailTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testThumbnail()
    {
        $thumbnail = new Thumbnail($this->_thumbnailFixure);

        $this->assertInstanceOf(Thumbnail::class, $thumbnail);

        $this->assertEquals($this->_thumbnailFixure['targetPath'], $thumbnail->getTargetPath());

        $this->assertEquals($this->_thumbnailFixure['resolution'], $thumbnail->getResolution());

        $this->assertEquals($this->_thumbnailFixure['width'], $thumbnail->getWidth());

        $this->assertEquals($this->_thumbnailFixure['height'], $thumbnail->getHeight());

        $this->assertEquals($this->_thumbnailFixure['extension'], $thumbnail->getExtension());

        $this->assertEquals($this->_thumbnailFixure['mimeType'], $thumbnail->getMimeType());

        $this->assertEquals($this->_thumbnailFixure['sourcePath'], $thumbnail->getSourcePath());
    }
}