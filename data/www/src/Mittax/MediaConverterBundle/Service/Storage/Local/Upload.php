<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 23:09
 */

namespace Mittax\MediaConverterBundle\Service\Storage\Local;

use Mittax\MediaConverterBundle\Event\Dispatcher;
use Mittax\MediaConverterBundle\Event\Upload\AssetUploadFinished;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class Upload
{

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

        mkdir($path . $folderName , 0777);

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
     */
    public function dispatchFinishedEvent(string $originalFileName)
    {
        $fileName = $this->sanitize_filename($originalFileName);

        $folderName = md5($fileName);

        $filePath = 'upload/' . $folderName . "/" . $fileName;

        $event = new AssetUploadFinished($filePath);

        Dispatcher::dispatch(AssetUploadFinished::NAME, $event);
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

    function readyToRead($file){
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
            '%3d'       // =
        );

        if ( ! $relative_path)
        {
            $bad[] = './';
            $bad[] = '/';
        }

        $str = self::remove_invisible_characters($str, FALSE);
        return strtolower(stripslashes(str_replace($bad, '', $str)));
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