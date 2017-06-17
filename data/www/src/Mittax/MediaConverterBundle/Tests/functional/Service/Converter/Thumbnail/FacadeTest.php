<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 28.12.16
 * Time: 16:53
 */

namespace Mittax\MediaConverterBundle\Tests\Service\Converter\Thumbnail;
use Mittax\MediaConverterBundle\Service\Converter\Thumbnail\Facade;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class Facade
 * @package Mittax\MediaConverterBundle\Repository\Converter\Thumbnail
 */
class FacadeTest extends AbstractKernelTestCase
{
    /**
     * @var Facade
     */
    private $_facade;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->_facade = $this->container->get('mittax_mediaconverter.service.converter.thumbnail.facade');
    }

    public function testFacadeInstance()
    {
        $this->assertInstanceOf(Facade::class, $this->_facade);
    }

    public function testGenerate()
    {
        $thumbnailData = $this->_facade->generate($this->_testPathList);

        $this->assertNotEmpty($thumbnailData);

        $this->assertNotEmpty($this->_facade->getJobTickets());
    }
}