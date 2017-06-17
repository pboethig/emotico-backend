<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 20:52
 */

namespace Mittax\MediaConverterBundle\Tests\Service\Uuid;


use Mittax\MediaConverterBundle\Service\Uuid\Generator;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class GeneratorTest extends AbstractKernelTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testGenerate()
    {
        $uuid = Generator::generate();

        $this->assertNotEmpty($uuid);
    }

}