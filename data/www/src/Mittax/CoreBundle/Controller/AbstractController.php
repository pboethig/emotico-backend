<?php

namespace Mittax\CoreBundle\Controller;

use Mittax\CoreBundle\Entity\IEntity;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AbstractController extends FOSRestController implements IController
{
 
    /**
     * @param IEntity $entity
     * @return Response
     */
    public function deleteByItem(IEntity $entity)
    {
        $this->get('mittax_core.repository.generic')->deleteByItem($entity);

        $response = array('message'=>'success');

        return new Response($response, \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    /**
     * @param IEntity $entity
     * @param ConstraintViolationListInterface|null $validationErrors
     * @return Response
     */
    public function persistAndSave(IEntity $entity, ConstraintViolationListInterface $validationErrors = null)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $entity->setId($request->get('id'));

        /**
         * Validate
         */
        if (!empty($errorArray = $this->validate($validationErrors)))
        {
            return new Response($errorArray, \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        }

        /**
         * Persist
         */
        $entityId = $this->container->get('mittax_core.repository.generic')->persistAndSave($entity);

        /**
         * Responde
         */
        $response = array('message'=>'success', 'return'=>['id' => $entityId]);

        return new Response($response, \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    /**
     * Handles ConstraintValidationListInterface from request
     *
     * @param ConstraintViolationListInterface|null $validationErrors
     * @return array
     */
    public function validate(ConstraintViolationListInterface $validationErrors = null) : array
    {
        $errorArray = [];

        if (count($validationErrors) > 0)
        {
            foreach($validationErrors as $error)
            {
                $errorArray['errors'][$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $errorArray;
    }

    /**
     * @param string $entityClassName
     * @return Response
     */
    public function fetchAll(string $entityClassName)
    {
        $items = $this->getGenericRepository()->fetchAll($entityClassName);

        $itemList = $this->getGenericRepository()->normalizeObjectList($items);

        return new JsonResponse($itemList, \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    /**
     * @return \Mittax\CoreBundle\Repository\Generic
     */
    public function getGenericRepository()
    {
        return $this->container->get('mittax_core.repository.generic');
    }
}
