<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 25.12.16
 * Time: 11:05
 */

namespace Mittax\MediaConverterBundle\Tests\Ticket\Converter\Cropping;

use Mittax\MediaConverterBundle\Service\Converter\Cropping\Facade;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;

/**
 * Class JobstTest
 * @package Mittax\MediaConverterBundle\Tests\ThumbnailTicket
 */
class CroppingTicketTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testProduce()
    {
        $testAsset = "test/croppingtest/23123123123_highres.tiff";

        $croppingData = new \stdClass();

        $croppingData->width = 200;
        $croppingData->height = 200;
        $croppingData->top = 10;
        $croppingData->left = 10;
        $croppingData->hash = "123123123213nmmnb123123";
        $croppingData->messurement='px';

        $facade = new Facade($testAsset, new CroppingData($croppingData));
        $result = $facade->produce();
        $this->assertTrue($result);
    }
}