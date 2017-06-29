<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 12.12.16
 * Time: 23:09
 */

namespace Mittax\MediaConverterBundle\Service\Storage\Local;

use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Service\Storage\FilesystemAbstract;
use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;
use Mittax\MediaConverterBundle\Service\System\Config;
use Mittax\MediaConverterBundle\ValueObjects\CroppingData;

class Filesystem extends FilesystemAbstract
{
    /**
     * @param string $rootDir
     * @return IAdapter
     */
    public static function getCachedAdapter(string $rootDir) : IAdapter
    {
        return new \Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\Flysystem\Local($rootDir);
    }

    /**
     * @return bool
     */
    public static function importFromUploadFolder(): bool
    {
        $content = Filesystem::getCachedAdapter('storage')->listContents('upload');

        foreach ($content as $item)
        {
            $folderName = md5($item['filename']);

            $assetAdapter = Filesystem::getCachedAdapter('storage/assets');

            $assetAdapter->createDir($folderName, new \League\Flysystem\Config());

            Filesystem::getCachedAdapter('storage')->copy('/upload/' .$item['basename'], '/assets/' . $folderName.'/'. $item['basename']);

            Filesystem::getCachedAdapter('storage/upload')->delete($item['basename']);
        }

        return true;
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public static function importPathFromUploadFolder(string $filePath): bool
    {
        $folderName = explode("/", $filePath)[1];

        $targetFolder = Config::getStoragePath() ."/assets/". $folderName;

        @mkdir($targetFolder);

        $targetPath = Config::getStoragePath()."/". str_replace("upload", "assets", $filePath);

        $filePath = Config::getStoragePath() ."/" . $filePath;

        @copy($filePath, $targetPath);

        return true;
    }

    /**
     * @param string $filePath
     * @return string
     * @throws \Exception
     */
    public function importBookPackagePathFromUploadFolder(string $filePath): string
    {
        /**
         * create final folder under assets
         */

        $fileName = str_replace('.inddbook','.indb', basename($filePath));

        $hash = Upload::md5($fileName);

        $targetFolderName = $hash;

        $targetFolderPath = Config::getStoragePath().'/assets/'.$targetFolderName;

        $filePath = Config::getStoragePath()."/".$filePath;

        if(!file_exists($filePath)) throw new \InvalidArgumentException("Packagefilepath does not exist: " .$filePath);

        /**
         * extract package
         */
        $zip = new \ZipArchive();
        $zip->open($filePath);

        $zip->extractTo($targetFolderPath);
        $zip->close();

        /**
         * Return indb file
         */
        foreach (new \DirectoryIterator($targetFolderPath) as $fileInfo)
        {
            if($fileInfo->isDot()) continue;

            if($fileInfo->getExtension()=="indb")
            {
                //rename extracted bookfile to filter filename
                rename($fileInfo->getRealPath(), $targetFolderPath.'/' . $fileName);

                return 'assets/' . $targetFolderName . '/' . $fileName;
            }
        }

        throw new \Exception("No indb file found in packagefolder:" . $targetFolderName);
    }

    /**
     * @param string $storagePath
     * @return string
     */
    public static function convertStoragePathToUrl(string $storagePath) : string
    {
        $storagePath = str_replace(Config::getExportPath(), Config::getPublicStorageUrl() . '/export', $storagePath);

        $storagePath = str_replace(Config::getAssetsPath(), Config::getPublicStorageUrl() . '/assets', $storagePath);

        $storagePath = str_replace(Config::getStoragePath(),"", $storagePath);

        return $storagePath;
    }

    /**
     * @param string $storagePath
     * @return string
     */
    public static function getUuidFromPath(string $storagePath) : string
    {
        $parts = explode("/", $storagePath);

        return $parts[1];
    }

    /**
     * @param StorageItem $storageItem
     * @param string $extension
     * @param string $context
     * @return string
     */
    public static function getStoragePath(StorageItem $storageItem, string $extension = '', string $context='assets') : string
    {
        if(empty($extension))
        {
            $extension = $storageItem->getExtension();
        }

        return Config::getStoragePath().'/' . $context . '/' . $storageItem->getUuid()."/" . $storageItem->getBasename() .'.' . $extension;
    }

    /**
     * @param StorageItem $storageItem
     * @param string $context
     * @return string
     */
    public static function createStorageFolder(StorageItem $storageItem, string $context='assets') : string
    {
        $folder = Config::getStoragePath() . '/' . $context . '/' . $storageItem->getUuid();

        if(!is_dir($folder))
        {
            mkdir($folder, 0777);
        }

        return $folder;
    }

    /**
     * @param StorageItem $storageItem
     * @param CroppingData $croppingData
     * @return string
     */
    public static function getHiresCroppingFilename(StorageItem $storageItem, CroppingData $croppingData)
    {
        return $storageItem->getBasename().'_'.$croppingData->getHash().'_crop.tiff';
    }
}