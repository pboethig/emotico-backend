<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.01.17
 * Time: 09:59
 */

namespace Mittax\MediaConverterBundle\Tests\Service\Metaata;


use Mittax\MediaConverterBundle\Collection\StorageItem;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Exif;
use Mittax\MediaConverterBundle\Service\Metadata\Generator\Factory;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

/**
 * Class ExifTest
 * @package Mittax\MediaConverterBundle\Tests\Service\Metaata
 */
class ExifProduceTest extends AbstractKernelTestCase
{

    /**
     * @var StorageItem
     */
    private $_jpgCollection;

    /**
     * @var StorageItem
     */
    private $_storageCollection;

    public function setUp()
    {
        parent::setUp();

        $storageRepository = $this->_storageRepositoryFactory->getByUuid($this->_storageRepositoryConfig->getUuid());

        $this->_storageCollection = $storageRepository->getCollection();

        $this->_jpgCollection = $this->_storageCollection->filterByPropertyNameAndValue('extension', 'jpg');
    }

    public function testExtract()
    {
        $tiffCollection = $this->_storageCollection->filterByPropertyNameAndValue('extension','jpg');

        $factory = new Factory($tiffCollection);

        $exifData = $factory->triggerCreation();

        //svg cant be supported
        $this->assertEmpty($exifData);
    }
}