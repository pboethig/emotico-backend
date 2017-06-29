<?php

namespace Mittax\MediaConverterBundle\Controller;

use Mittax\MediaConverterBundle\Repository\Converter\Cropping\Imagine\Ticket\Consumer;
use Mittax\MediaConverterBundle\Service\Converter\Cropping\Facade;
use Mittax\MediaConverterBundle\Service\Storage\Local\Upload;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\ValueObjects\BrowserImageData;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class AssetController
 * @package Mittax\MediaConverterBundle\Controller
 */
class AssetController extends AbstractController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="store an asset",
     *  section = "Assets",
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
                    $fileName = $uploadService->upload($file);

                    $uploadService->dispatchFinishedEvent($fileName);
                }

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
     *  description="store a base64 image",
     *  section = "Assets",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="retriving failed"
     *  },
     * )
     * @Route("/assets/storeBase64Image")
     */
    public function storeBase64Image(Request $request)
    {
        $response = new Response();

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

        }
        catch (\Exception $ex)
        {
            $response->setContent('{"error":"'.$ex->getMessage().$ex->getTraceAsString().'"}');

            $response->setStatusCode(500);

            return $response;
        }
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="trigger import",
     *  section = "Assets",
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
     *  section = "Assets",
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

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="generates highres cropping: ",
     *  section = "Assets",
     *  statusCodes={
     *     200="Returned when successful",
     *     500="download failed"
     *  },
     * )
     * @Route("/assets/generateHiresCropping")
     *
     * @Method({"POST"})
     * {"asset":{"id":932,"uuid":"test123","version":"testasset","extension":".jpg","created_at":"2017-06-27 13:00:32","updated_at":"2017-06-27 13:00:32","thumbnailList":"[\"thumb1.jpg\",\"thumb2.jpg\"]"},"canvasdata":{"top":10,"left":10,"width":10,"height":10,"messurement":"px"}}
     * @todo: create request validator and move validation logic
     */
    public function generateHiresCropping(Request $request)
    {
        try
        {
            $json = json_decode($request->getContent());

            $asset = $json->asset;

            $canvasdata = $json->canvasdata;

            $browserimagedata = $json->browserimagedata;

            $this->validateCroppingData($canvasdata, $asset);

            $storagePath = 'assets/' . $asset->uuid . '/' . $asset->version . '.' . $asset->extension;

            if(!file_exists(Config::getStoragePath() . '/' .$storagePath))
            {
                $response = new Response('file: ' . $storagePath . ' not found');

                $response->setStatusCode(404);

                return $response;
            }

            $croppingFacade = new Facade($storagePath, new CroppingData($canvasdata), new BrowserImageData($browserimagedata));

            $croppingFacade->produce();
            
            //$consumer = new Consumer($croppingFacade->getTickets());

            //$consumer->execute();
        }
        catch (\Exception $ex)
        {
            return new Response($ex->getMessage() . $ex->getTraceAsString(), 500);
        }

        return ['message'=>'success'];
    }

    /**
     * @param \stdClass $canvasdata
     * @param \stdClass $asset
     */
    private function validateCroppingData(\stdClass $canvasdata, \stdClass $asset)
    {
        if(!isset($asset->version)) throw new \InvalidArgumentException('request object asset invalid. property version missing');

        if(!isset($asset->version)) throw new \InvalidArgumentException('request object asset invalid. property uuid missing');

        if(!isset($canvasdata->width)) throw new \InvalidArgumentException('request object canvasdata invalid. property width missing');

        if(!isset($canvasdata->height)) throw new \InvalidArgumentException('request object canvasdata invalid. property height missing');

        if(!isset($canvasdata->top)) throw new \InvalidArgumentException('request object canvasdata invalid. property top missing');

        if(!isset($canvasdata->left)) throw new \InvalidArgumentException('request object canvasdata invalid. property left missing');

        if(!isset($canvasdata->hash)) throw new \InvalidArgumentException('request object canvasdata invalid. hash missing');


    }
}
