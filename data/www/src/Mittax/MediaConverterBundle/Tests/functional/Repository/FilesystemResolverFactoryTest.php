<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 16.12.16
 * Time: 12:32
 */

namespace Mittax\MediaConverterBundle\Tests\Repository\Storage;

use Mittax\MediaConverterBundle\Repository\Storage\FilesystemResolverFactory;
use Mittax\MediaConverterBundle\Service\Storage\IResolver;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class FilesystemResolverFactoryTest extends AbstractKernelTestCase
{
    
    public function setUp()
    {
        parent::setUp();
    }

    public function testResolverFactory()
    {
        $filesystemResolverFactory = new FilesystemResolverFactory();
        
        $this->assertNotEmpty($filesystemResolverFactory::getMediaConverterConfig());

        $this->assertInstanceOf(IResolver::class, $filesystemResolverFactory->getResolver());
    }
}