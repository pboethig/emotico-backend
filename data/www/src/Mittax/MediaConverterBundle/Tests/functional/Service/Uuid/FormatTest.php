<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:52
 */

namespace Mittax\MediaConverterBundle\Tests\Service\Uuid;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\Format;

/**
 * Class StorageItem
 * @package Mittax\MediaConverterBundle\Tests\Service\Uuid
 */
class FormatTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testGenerate()
    {
        $item = new Format($this->_formatFixure);

        $generator = new \Mittax\MediaConverterBundle\Service\Uuid\Format($item);

        $this->assertInstanceOf(\Mittax\MediaConverterBundle\Service\Uuid\Format::class, $generator);

        $uuid = $generator->generate();

        $this->assertNotEmpty($uuid);
    }
}