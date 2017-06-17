<?php

namespace Mittax\MediaConverterBundle\Controller;


use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\InDesignServer\InDesignServerLowresCreated;
use Mittax\MediaConverterBundle\Service\InDesignServer\Event;
use Mittax\MediaConverterBundle\Service\Logger\Factory;
use Mittax\MediaConverterBundle\Service\System\Config;
use Nelmio\ApiDocBundle\Tests\Functional\AppKernel;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Mittax\CoreBundle\Controller\AbstractController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Verbs
 */
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
/**
 * FOS
 */
use FOS\RestBundle\Controller\Annotations;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class DefaultController
 * @package EmoticoBundle\EmoticoBundle\Controller
 */
class DefaultController extends AbstractController
{
    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="retrieve a eventhookmessage from indesign server",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving indesign data failed"
     *  },
     * )
     *
     * @Route("/webhooks/indesignserver")
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {
        try
        {
            $InDesignEvents = new Event($this->container);

            $response = $InDesignEvents->buildTypedResponseFromJson($request->getContent());

            $InDesignEvents->dispatch($response);

        }catch (\Exception $ex)
        {
            return new Response($ex->getMessage().$ex->getTraceAsString(), 500);
        }

        return ['success'];
    }
}