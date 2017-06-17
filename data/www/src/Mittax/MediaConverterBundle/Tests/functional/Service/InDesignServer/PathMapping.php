<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.01.17
 * Time: 09:59
 */
namespace Mittax\MediaConverterBundle\Tests\Service\InDesignServer;
use Mittax\MediaConverterBundle\Service\InDesignServer\PathMapping;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class ExifTest
 * @package Mittax\MediaConverterBundle\Tests\Service\Metaata
 */
class Event extends AbstractKernelTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetStorageUrl()
    {
        //\\vmware-host\Shared Folders\shared_storage
        $path = "\\\\vmware-host\\Shared Folders\\shared_storage\\export\\6e53364f7d34d75121f72807dfe4938e\\Document.ExportJPG_highres_10.jpg";

        $IndesignPathService = new PathMapping();

        $publicUrl = $IndesignPathService->convertNetworkPathToUrl($path);

        $this->assertTrue(strpos($publicUrl, '://')>-1);

        $this->assertTrue(strpos($publicUrl, '/export/')>-1);
    }
}