<?php

namespace Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\Flysystem;
use Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\IAdapter;

/**
 * Class Local
 * @package Mittax\MediaConverterBundle\Service\Storage\Local\Adapter\Flysystem
 */
class Local extends \League\Flysystem\Adapter\Local implements IAdapter
{
    /**
     * @var \League\Flysystem\Adapter\Local
     */
    public $fileSystem;

    /**
     * Local constructor.
     * @param string $rootDir
     */
   public function __construct(string $rootDir)
   {
       if (!is_dir($rootDir))
       {
           mkdir($rootDir, 0777);
       }

       $permissions = [
           'file' => [
               'public' => 0777,
               'private' => 0777,
           ],
           'dir' => [
               'public' => 0777,
               'private' => 0777,
           ]
       ];

       parent::__construct($rootDir , LOCK_EX, parent::DISALLOW_LINKS, $permissions);
   }

    
    public function getMetadata($path)
    {
        $metadata = parent::getMetadata($path);

        return $metadata;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return array
     */
    protected function mapFileInfo(\SplFileInfo $file)
    {
        $normalized = [
            'type' => $file->getType(),
            'path' => $this->getFilePath($file),
            'extension' => $file->getExtension(),
            'basename'=>$file->getBasename(),
            'filename'=>$file->getFilename(),
            'dirname'=>rtrim(str_replace($file->getBasename(),"", $this->getFilePath($file)),"/")
        ];

        $normalized['timestamp'] = $file->getMTime();

        if ($normalized['type'] === 'file') {
            $normalized['size'] = $file->getSize();
        }

        return $normalized;
    }


}