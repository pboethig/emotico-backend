<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 26.10.16
 * Time: 23:55
 */

namespace Mittax\CoreBundle\Entity;



use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use JMS\Serializer\SerializationContext;

class EntityAbstract implements IEntity
{

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}