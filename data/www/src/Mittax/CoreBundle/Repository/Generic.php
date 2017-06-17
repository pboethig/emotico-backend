<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.11.16
 * Time: 14:12
 */

namespace Mittax\CoreBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Mittax\CoreBundle\Entity\IEntity;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\HttpFoundation\Response;

class Generic implements IEntityRepository
{
    /**
     * @var EntityManager
     */
    private $_entityManager;

    /**
     * @var Serializer
     */
    private $_serializer;

    /**
     * Generic constructor.
     * @param EntityManager $entityManager
     * @param Serializer $serializer
     */
    public function __construct(EntityManager $entityManager, Serializer $serializer)
    {
        $this->_serializer = $serializer;

        $this->_entityManager = $entityManager;
    }

    /**
     * @param IEntity $entity
     * @return int
     */
    public function persistAndSave(IEntity $entity) : int
    {
        /**
         * Update Item
         */
        if($entity->getId() > 0)
        {
            $this->_entityManager->merge($entity);

            $this->_entityManager->flush();

            return $entity->getId();
        }

        /**
         * Add Item
         */
        $this->_entityManager->persist($entity);

        $this->_entityManager->flush();

        return $entity->getId();
    }

    /**
     * @param IEntity $entity
     * @return bool
     * @throws EntityNotFoundException
     */
    public function deleteByItem(IEntity $entity) : bool
    {
        $row = $this->findOneBy(get_class($entity), ['id' => $entity->getId()]);

        if (!$row)
        {
            throw new EntityNotFoundException('Id: '. $entity->getId() . ' not found');
        }

        $this->_entityManager->remove($entity);

        $this->_entityManager->flush();

        return true;
    }

    /**
     * @param string $entityClassName
     * @return array
     */
    public function fetchAll(string $entityClassName) : array
    {
        return $this->getEntityRepository($entityClassName)->findAll();
    }

    /**
     * @param IEntity $entity
     * @return string
     */
    public function toJson(IEntity $entity) : string
    {
        $context = SerializationContext::create()->enableMaxDepthChecks();

        return $this->_serializer->serialize($entity, 'json', $context);
    }

    /**
     * @param IEntity $entity
     * @return Response
     */
    public function toJsonResponse(IEntity $entity) : Response
    {
        return new Response($this->toJson($entity));
    }

    /**
     * Converts array of IEntity to json string
     *
     * @param array IEntity[] $items
     * @return string
     */
    public function toJsonObjectList(array $items) : string
    {
        $genericRepository = $this;

        $callback = function ($item) use ($genericRepository)
        {
            return $genericRepository->toJson($item);
        };

        $stringArray = array_map($callback, $items);

        /**
         * Prevent JSON decode by building json objectlist by foot
         */
        $objectListAsJsonString = "[";

        foreach ($stringArray as $jsonObject)
        {
            $objectListAsJsonString .= $jsonObject . ',';
        }

        $objectListAsJsonString = rtrim($objectListAsJsonString, ',');

        $objectListAsJsonString .= "]";

        return $objectListAsJsonString;
    }

    /**
     * @param string $entityClassName
     * @param array $criteria
     * @param array|null $orderBy
     * @return null|object
     * @throws EntityNotFoundException
     */
    public function findOneBy(string $entityClassName, array $criteria, array $orderBy = null)
    {
        $item = $this->getEntityRepository($entityClassName)->findOneBy($criteria, $orderBy);

        if(!$item) throw new EntityNotFoundException('no row with that id found');

        return $item;
    }

    /**
     * @param string $entityClassName
     * @return EntityRepository
     */
    public function getEntityRepository(string $entityClassName) : EntityRepository
    {
        return new EntityRepository($this->_entityManager, new ClassMetadata($entityClassName));
    }

    /**
     * @param array $items IEntity[]
     * @return array
     */
    public function normalizeObjectList(array $items) : array
    {
        return json_decode($this->toJsonObjectList($items));
    }
}