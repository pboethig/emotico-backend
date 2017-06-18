<?php

namespace Mittax\MediaConverterBundle\Controller;

use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
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


class AssetController extends Controller
{
    use ContainerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="store an asset",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     *
     * @Route("/assets/store")
     */
    public function storeAction(Request $request)
    {
        $response = new Response('{"message":"success"}');

        try
        {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Credentials', 'false');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Cache-Control, Accept, Origin, X-Session-ID');
            $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,HEAD,DELETE,TRACE,COPY,LOCK,MKCOL,MOVE,PROPFIND,PROPPATCH,UNLOCK,REPORT,MKACTIVITY,CHECKOUT,MERGE,M-SEARCH,NOTIFY,SUBSCRIBE,UNSUBSCRIBE,PATCH,OPTIONS');

            if($request->files->get('file'))
            {
                $uploadService = new Upload($this->container);

                foreach ($request->files->get('file') as $file)
                {
                    $uploadService->upload($file);
                }

                return $response;
            }

            return $response;

        }catch (\Exception $ex)
        {
            $response->setContent('{"error":"'.$ex->getMessage().'"}');

            $response->setStatusCode(500);

            return $response;
        }
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="display upload view",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     *
     * @Route("/assets/upload")
     * @Method({"GET"})
     */
    public function uploadAction()
    {
        $response = new Response(
            $this->renderView('MittaxMediaConverterBundle:Asset:upload.html.twig',
                array(
                    'publicWebUrl'=>Config::getPublicWebUrl(),
                    'publicWebSocketUrl'=>Config::getPublicWebSocketUrl()
                )),
            200
        );

        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="trigger import",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="trigger failed"
     *  },
     * )
     *
     * @Route("/assets/process")
     * @Method({"GET"})
     */
    public function processAction(Request $request)
    {
        $response = new Response('{"message":"success"}');

        try
        {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Credentials', 'false');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Cache-Control, Accept, Origin, X-Session-ID');
            $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,HEAD,DELETE,TRACE,COPY,LOCK,MKCOL,MOVE,PROPFIND,PROPPATCH,UNLOCK,REPORT,MKACTIVITY,CHECKOUT,MERGE,M-SEARCH,NOTIFY,SUBSCRIBE,UNSUBSCRIBE,PATCH,OPTIONS');

            $uploadService = new Upload($this->container);

            $uploadService->dispatchFinishedEvent($request->get('file'));

            $response->setContent(json_encode(['success'=>$request->get('file') .' event dispatched']));

        }catch (\Exception $ex)
        {
            $response->setContent('{"error":"'.$ex->getMessage().$ex->getTraceAsString().'"}');

            $response->setStatusCode(500);
        }
        return $response;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="outputs asset highres",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="download failed"
     *  },
     * )
     *
     * @Route("/assets/{path}/downloadHighres")
     *
     * @Method({"GET"})
     */
    public function downloadHighresAction(string $path, Request $request)
    {
        try
        {
            $file = base64_decode($path);

            $response = new BinaryFileResponse(Config::getStoragePath() . "/" . $file);

            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        }
        catch (\Exception $ex)
        {
            return new Response($ex->getMessage() . $ex->getTraceAsString(), 500);
        }

        return $response;
    }
}
