<?php

namespace Mittax\MediaConverterBundle\Controller;

use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\RabbitMQBundle\Service\Api\Queue;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Process\Process;


class QueueController extends Controller
{
    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="get rabbitmq info about a queue",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     * @Method("GET")
     * @Route("/queue/{name}/info")
     */
    public function queueInfoAction(string $name,Request $request)
    {
        $response = $this->getCoresResponse();

        try
        {
            /** @var  $queueApiService Queue */
            $queueApiService = $this->container->get('mittax.rabbitmqbundle.service.api.queue');

            $queueApiService->getQueue($name);

            $response->setContent($queueApiService->toJson());

            return $response;

        }catch (\Exception $ex)
        {
            $response->setContent($ex->getMessage());

            $response->setStatusCode(500);

            return $response;
        }
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="starts a messagequeueconsumer",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     * @Method("GET")
     * @Route("/queue/{command}/startConsumer")
     */
    public function startConsumer(string $command,Request $request)
    {
        $response = $this->getCoresResponse();

        try
        {
            $allowedCommands = [
                'mittax:mediaconverter:thumbnail:imagine:startconsumer',
                'mittax:mediaconverter:thumbnail:ffmpeg:startconsumer',
            ];

            if(!in_array($command, $allowedCommands))
            {
                $response->setContent('{"error":"not allowed"}');
                $response->setStatusCode(403);
                return $response;
            }
            
            $processes = new Process("php /var/www/app/console " . $command );

            $processes->setTimeout(81400);

            $processes->run();

            $processes->wait(function ($type, $buffer) {});

            $response->setStatusCode(200);

            $response->setContent('{"message":"success"}');

            return $response;

        }catch (\Exception $ex)
        {
            $response->setContent($ex->getMessage());

            $response->setStatusCode(500);

            return $response;
        }
    }

    /**
     * @return Response
     */
    private function getCoresResponse()
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'false');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Cache-Control, Accept, Origin, X-Session-ID');
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,HEAD,DELETE,TRACE,COPY,LOCK,MKCOL,MOVE,PROPFIND,PROPPATCH,UNLOCK,REPORT,MKACTIVITY,CHECKOUT,MERGE,M-SEARCH,NOTIFY,SUBSCRIBE,UNSUBSCRIBE,PATCH,OPTIONS');

        return $response;
    }
}
