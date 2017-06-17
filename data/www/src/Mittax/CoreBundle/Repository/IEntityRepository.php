<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 13.11.16
 * Time: 14:17
 */

namespace Mittax\CoreBundle\Repository;


use Mittax\CoreBundle\Entity\IEntity;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

interface IEntityRepository
{
    /**
     * @param IEntity $entity
     * @return mixed
     */
    public function persistAndSave(IEntity $entity) : int;

    /**
     * @param IEntity $entity
     * @return mixed
     */
    public function deleteByItem(IEntity $entity) : bool;

    /**
     * @param string $bundleNamespace
     * @return array
     */
    public function fetchAll(string $bundleNamespace) : array;

    /**
     * @param IEntity $entity
     * @return string
     */
    public function toJson(IEntity $entity) : string;

    /**
     * @param IEntity $entity
     * @return Response
     */
    public function toJsonResponse(IEntity $entity) : Response;

    /**
     * @param array $items
     * @return string
     */
    public function toJsonObjectList(array $items): string;
    
    
}