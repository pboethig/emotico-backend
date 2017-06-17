<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.11.16
 * Time: 14:34
 */

namespace Mittax\CoreBundle\Tests\Service\Repository;


use Mittax\CoreBundle\Repository\Generic;
use Mittax\CoreBundle\Repository\IEntityRepository;
use Mittax\CoreBundle\Repository\IRepository;
use Mittax\EmoticoBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenericTest extends KernelTestCase
{
    /**
     * @var int
     */
    public static $ItemId;

    /**
     * @var ContainerInterface
     */
    protected $_container;

    /**
     * @var Generic
     */
    protected $_genericRepository;


    public function setUp()
    {
        self::bootKernel();

        $this->_container = self::$kernel->getContainer();

        $this->_genericRepository = $this->_container->get('mittax_core.repository.generic');

        parent::setUp();
    }

    private function getItem()
    {
        $item = new Item();

        $item->setTitle("a title");

        $item->setDescription("a description");

        $item->setCreatedAt(new \DateTime());

        $item->setGroupid(1);

        $item->setUserid(1);

        $item->setIconpaths(['small'=>'$mediaPath/icons/small.jpg','medium'=>'$mediaPath/icons/small.jpg','large'=>'$mediaPath/icons/small.jpg']);

        return $item;
    }


    /**
     * Tests if generic repository implements IRepository
     */
    public function testImplementsIRepositoryInterface()
    {
        $reflectionClass = new \ReflectionClass($this->_genericRepository);

        $this->assertTrue($reflectionClass->implementsInterface(IEntityRepository::class));
    }

    /**
     * Testing add and updating
     */
    public function testPersistAndSave()
    {
        $emotico = $this->getItem();

        /**
         * test add
         */
        self::$ItemId = $this->_genericRepository->persistAndSave($emotico);

        $this->assertGreaterThan(0, self::$ItemId);

        /**
         * test update
         */
        $emotico->setId(self::$ItemId);

        $id_update = $this->_genericRepository->persistAndSave($emotico);

        $this->assertEquals(self::$ItemId, $id_update);
    }

    public function testFindOneBy()
    {
        $criteria = ['id' => self::$ItemId];

        /** @var  $item Item*/
        $item = $this->_genericRepository->findOneBy(Item::class, $criteria);

        $this->assertEquals(self::$ItemId, $item->getId());
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function testFindOneByFails()
    {
        $criteria = ['id' => 1000000000000000000000];

        $this->_genericRepository->findOneBy(Item::class, $criteria);
    }

    public function testFetchAll()
    {
        $bundleNameSpace = 'MittaxEmoticoBundle:Item';

        /** @var $objectList Item[] */
        $objectList = $this->_genericRepository->fetchAll($bundleNameSpace);

        $this->assertNotEmpty($objectList);

        $firstItem = $objectList[0];

        $this->assertGreaterThan(0, $firstItem->getId());
    }

    public function testFetchAllToJson()
    {
        $bundleNameSpace = 'MittaxEmoticoBundle:Item';

        /** @var $objectList Item[] */
        $objectList = $this->_genericRepository->fetchAll($bundleNameSpace);

        $this->assertNotEmpty($objectList);

        $firstItem = $objectList[0];

        $this->assertGreaterThan(0, $firstItem->getId());

        $objectListAsJson = $this->_genericRepository->toJsonObjectList($objectList);

        /** @var  $firstItem Item */
        $firstItem = json_decode($objectListAsJson)[0];

        $this->assertGreaterThan(0, $firstItem->id);
    }

    /**
     * Test deletion on the generic repository
     */
    public function testDeleteItemById()
    {
        $criteria = ['id' => self::$ItemId];

        /** @var  $item Item*/
        $item = $this->_genericRepository->findOneBy(Item::class, $criteria);

        $result = $this->_genericRepository->deleteByItem($item);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     * Test deletion on the generic repository
     */
    public function testFailingDeleteItemById()
    {
        $item = new Item();
        $item->setId(213123123123);

        $result = $this->_genericRepository->deleteByItem($item);

        $this->assertTrue($result);
    }


    public function testToJsonObjectList()
    {
        $item = new Item();
        $item->setId(213123123123);

        $item1 = new Item();
        $item1->setId(2131123123123123123);

        $list = [$item, $item1];

        $list = $this->_genericRepository->toJsonObjectList($list);

        $this->assertNotEmpty($list);

        $this->assertCount(2, json_decode($list));

    }

    public function testNormalizeObjectList()
    {
        $item = new Item();
        $item->setId(213123123123);

        $item1 = new Item();
        $item1->setId(2131123123123123123);

        $list = [$item, $item1];

        $list = $this->_genericRepository->normalizeObjectList($list);

        $this->assertCount(2, $list);
    }
}