<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 17.12.16
 * Time: 11:46
 */

namespace Mittax\MediaConverterBundle\Tests\ValueObjects;

use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;
use Mittax\MediaConverterBundle\ValueObjects\Version;

class VersionTest extends AbstractKernelTestCase
{
    /**
     * @var Version
     */
    private $_version;

    public function setUp()
    {
        parent::setUp();

        $this->_version = new Version('1.2.3');
    }

    public function testFormatInstance()
    {
        $this->assertInstanceOf(Version::class, $this->_version);
    }

    public function testFormatMethods()
    {
        $this->assertEquals(1, $this->_version->getMajor());

        $this->assertEquals(2, $this->_version->getMinor());

        $this->assertEquals(3, $this->_version->getPatch());

        $this->assertEquals('1.2.3', $this->_version->getVersionString());
    }
}