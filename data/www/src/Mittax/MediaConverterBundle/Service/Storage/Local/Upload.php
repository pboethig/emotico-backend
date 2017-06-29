<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 23:09
 */

namespace Mittax\MediaConverterBundle\Service\Storage\Local;

use Illuminate\Support\Facades\Request;
use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\Upload\BookPackageUploadFinished;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Mittax\MediaConverterBundle\Event\Upload\AssetUploadFinished;

class Upload
{
    /**
     * @var array
     */
    private $extensionToEventMap = [
      '.inddbook'=>[
          'eventClass'=>'Mittax\MediaConverterBundle\Event\Upload\BookPackageUploadFinished',
          'eventMethod'=>'onBookPackageUploadFinished'
      ]
    ];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Upload constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return UploadedFile
     */
    public function storeBase64File(\Symfony\Component\HttpFoundation\Request $request)
    {
        $filePath = sys_get_temp_dir() .'/'.$request->get('filename');

        @unlink($filePath);

        $parts = explode(",", $request->get('base64Image'));

        file_put_contents($filePath, base64_decode($parts[1]));

        return new UploadedFile($filePath, $request->get('filename'), null, filesize($filePath), null, true);
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = $this->sanitize_filename($file->getClientOriginalName());

        $path = "/var/www/storage/upload/";

        /**
         * @todo: check why flysystem adapter doesnt work here
         */
        $folderName = self::md5($file->getClientOriginalName());

        @unlink($path . $folderName);

        @mkdir($path . $folderName , 0777);

        $file->move($path.$folderName, $fileName);

        return $fileName;


    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function md5(string $fileName) : string
    {
        return md5(self::sanitize_filename($fileName));
    }

    /**
     * @param string $originalFileName
     * @return bool
     */
    public function dispatchFinishedEvent(string $originalFileName) : bool
    {
        $fileName = $this->sanitize_filename($originalFileName);

        $folderName = md5($fileName);

        $filePath = 'upload/' . $folderName . "/" . $fileName;

        if($this->dispatchPackageEventOnMatchingExtension($fileName, $filePath))
        {
            return true;
        }

        $event = new AssetUploadFinished($filePath);

        Dispatcher::getInstance()->dispatch(AssetUploadFinished::NAME, $event);

        return true;
    }

    /**
     * @param string $fileName
     * @param string $filePath
     * @return bool
     */
    public function dispatchPackageEventOnMatchingExtension(string $fileName, string $filePath) : bool
    {
        foreach ($this->extensionToEventMap as $extension=>$eventData)
        {
            if(strpos($fileName, $extension)>-1)
            {
                $event = new $eventData['eventClass']($filePath);

                $dispatcher = new EventDispatcher();

                $listener = new \Mittax\MediaConverterBundle\Event\Listener\Upload\BookPackageUploadFinished($this->container);

                $dispatcher->addListener($event::NAME, array($listener, $eventData['eventMethod']));

                $dispatcher->dispatch($event::NAME, $event);

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $rootPath
     * @return array
     */
    public function getPathList(string $rootPath)
    {
        $fileinfos = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath)
        );

        $assetPaths = [];

        foreach($fileinfos as $pathname => $fileinfo)
        {
            /** @var $fileinfo SplFileInfo   */
            if($fileinfo->isFile())
            {
                $assetPaths[] = 'upload/' . $fileinfo->getFilename();
            }
        }

        return $assetPaths;
    }

    /**
     * @param $file
     * @return bool
     */
    function readyToRead($file)
    {
        return ((time() - filemtime($file)) > 5 ) ? true : false;
    }

    /**
     * Sanitize Filename
     *
     * @param   string  $str        Input file name
     * @param   bool    $relative_path  Whether to preserve paths
     * @return  string
     */
    public static function sanitize_filename($str, $relative_path = FALSE)
    {
        $bad = array(
            '../', '<!--', '-->', '<', '>',
            "'", '"', '&', '$', '#',
            '{', '}', '[', ']', '=',
            ';', '?', '%20', '%22',
            '%3c',      // <
            '%253c',    // <
            '%3e',      // >
            '%0e',      // >
            '%28',      // (
            '%29',      // )
            '%2528',    // (
            '%26',      // &
            '%24',      // $
            '%3f',      // ?
            '%3b',      // ;
            '%3d',       // =
            ' ',
        );

        if ( ! $relative_path)
        {
            $bad[] = './';
            $bad[] = '/';
        }

        $str = self::remove_invisible_characters($str, FALSE);

        $filePath = strtolower(stripslashes(str_replace($bad, '', $str)));

        $filePath = self::supportReuploads($filePath);

        return $filePath;
    }

    /**
     * @param string $filePath
     * @return mixed|string
     */
    public static function supportReuploads(string $filePath)
    {
        //remove all dots and extensions in the middle of the filename and read extension
        $parts = explode(".",$filePath);

        $extension = end($parts);

        $filePath = str_replace(".","",$filePath);

        $filePath = str_replace($extension,"",$filePath).".".$extension;

        return $filePath;
    }

    /**
     * @param $str
     * @param bool $url_encoded
     * @return mixed
     */
    public static function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded)
        {
            $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127

        do
        {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        }

        while ($count);

        return $str;
    }
}