<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 14.12.16
 * Time: 21:36
 */

namespace Mittax\MediaConverterBundle\Tests\Collection;

use Mittax\MediaConverterBundle\Collection\Thumbnail as ThumbnailCollection;
use Mittax\MediaConverterBundle\Entity\Thumbnail\Thumbnail;
use Mittax\MediaConverterBundle\Tests\AbstractKernelTestCase;

class ThumbnailTest extends AbstractKernelTestCase
{
    /**
     * @var int
     */
    private $_repeats = 100;

    protected $uuids;

    public function setUp()
    {
        parent::setUp();
    }

    public function testCollectionInstance()
    {
        //$probe = parent::startBlackFire(4);

        $items=[];

        $uuids = [];

        $thumbnailItem = null;

        for ($i = 0; $i < $this->_repeats; $i++)
        {
            $thumbnailItem = new Thumbnail($this->getThumbnailFixure($i));

            $uuids[] =  $thumbnailItem->getUuid();

            $items[] = $thumbnailItem;
        }

        $collection = new ThumbnailCollection($items);

        //parent::stopBlackFire($probe);

        /**
         * test last item
         */
        $this->assertEquals($thumbnailItem->getUuid(), $collection->getLastItem()->getUuid());

        /**
         * test firstItem
         */
        $this->assertEquals($uuids[0], $collection->getFirstItem()->getUuid());

        /**
         * Test count after loop
         */
        $this->assertEquals($this->_repeats, $collection->count());

        /**
         * Test filtering
         */
        $filteredCollection = $collection->filterByPropertyNameAndValue('sourcePath', $collection->getFirstItem()->getSourcePath());
        $this->assertEquals(1, $filteredCollection->count());
    }


    public function tearDown()
    {
        parent::tearDown();
    }
}