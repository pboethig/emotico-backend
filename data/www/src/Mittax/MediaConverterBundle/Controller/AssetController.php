<?php

namespace Mittax\MediaConverterBundle\Controller;

use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class AssetController extends AbstractController
{
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
        $response = $this->getCoresResponse();
        $response->setContent('{"message":"success"}');

        try
        {
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
     *  description="store a base64 image",
     *  section = "MediaConverter",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     *
     * @Route("/assets/storeBase64Image")
     */
    public function storeBase64Image(Request $request)
    {
        $response = $this->getCoresResponse();
        $response->setContent('{"message":"success"}');

        try
        {
            if(empty($request->get('filename'))) throw new \InvalidArgumentException('No filename set');

            if(empty($request->get('base64Image'))) throw new \InvalidArgumentException('No base64Image set');

            if($request->get('filename') && $request->get('base64Image'))
            {
                $uploadService = new Upload($this->container);

                $file = $uploadService->storeBase64File($request);

                $uploadService->upload($file);

                $uploadService->dispatchFinishedEvent($request->get('filename'));

                return $response;
            }


            return $response;

        }catch (\Exception $ex)
        {
            $response->setContent('{"error":"'.$ex->getMessage().$ex->getTraceAsString().'"}');

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
        $response = $this->getCoresResponse();

        $response->setContent('{"message":"success"}');

        try
        {

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
