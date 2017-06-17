<?php
/**
 * Created by PhpStorm.
 * User: pboethig
 * Date: 20.11.16
 * Time: 13:04
 */

namespace Mittax\MediaConverterBundle\Tests;

use Imagine\Image\Box;
use Mittax\MediaConverterBundle\Collection\Metadata;
use Mittax\MediaConverterBundle\Entity\Metadata\MetadataItem;
use Mittax\MediaConverterBundle\Entity\Storage\StorageItem;
use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Imagine\Ticket\Builder;
use Mittax\MediaConverterBundle\Repository\Metadata\ItemRepository;
use Mittax\MediaConverterBundle\Repository\Metadata\RepositoryConfig;
use Mittax\MediaConverterBundle\Ticket\ITicket;

use Mittax\MediaConverterBundle\Repository\Storage\Factory;
use Mittax\MediaConverterBundle\Repository\Storage\FilesystemResolverFactory;
use Mittax\MediaConverterBundle\Repository\Storage\StorageRepositoryConfig;

use Mittax\MediaConverterBundle\Repository\Converter\Thumbnail\Factory as thumbnailConverterFactory;

use Mittax\MediaConverterBundle\Service\Storage\Local\Filesystem;
use Mittax\MediaConverterBundle\ValueObjects\FormatToMimeTypeList;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/../../../../app/autoload.php';
require_once __DIR__ . '/../../../../app/bootstrap.php.cache';
require_once __DIR__ . '/../../../../app/AppKernel.php';

class AbstractKernelTestCase extends BaseTest
{
    /**
     * @var \Blackfire\Probe
     */
    protected $_probe;

    /**
     * @var array
     */
    protected $_formatFixure=[];

    /**
     * @var FilesystemResolverFactory
     */
    protected $_resolverFactory;

    /**
     * @var StorageRepositoryConfig
     */
    protected $_storageRepositoryConfig;

    /**
     * @var \Mittax\MediaConverterBundle\Repository\Metadata\Factory
     */
    protected $_metadataRepositoryFactory;

    /**
     * @var RepositoryConfig
     */
    protected $_metadataRepositoryConfig;
    /**
     * @var array
     */
    protected $_thumbnailFixure;
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var KernelInterface
     */
    protected $_kernel;

    /**
     * @var thumbnailConverterFactory
     */
    protected $_thumbnailConverterFactory;

    /**
     * @var string
     */
    protected $_storagePath;

    /**
     * @var array
     */
    protected $_testPathList;

    /**
     * @var array
     */
    protected $_flySystemItemMock;

    /**
     * @var Factory
     */
    protected $_storageRepositoryFactory;

    /**
     * @var FormatToMimeTypeList
     */
    protected $_formatToMimeTypeList;

    /**
     * @var
     */
    protected $_isBlackFireTracked = false;

    /**
     * @var array
     */
    protected $_thumbnailJobFixure;
    /**
     * @var array
     */
    protected $_mediaConverterConfig;

    /**
     * @var ITicket
     */
    protected $_jobTicketFixure;

    public function setUp()
    {
        parent::setUp();

        $this->setKernel();

        $this->setContainer();

        $this->setThumbnailFixure();

        $this->setStoragePath();

        $this->setTestImages();

        $this->setFlySystemItemMock();

        $this->setResolverFactory();

        $this->setStorageRepositoryConfig();

        $this->setStorageRepositoryFactory();

        $this->setFormatFixure();

        $this->setFormatToMimeTypeList();

        $this->setThumbnailJobFixure();

        $this->setJobTicketFixure();

        $this->setMetadataRepositoryConfig();
    }

    public function buildConverterFactory()
    {
        $storageRepositoryConfig = new StorageRepositoryConfig($this->_testPathList);

        $this->_thumbnailConverterFactory = new thumbnailConverterFactory($storageRepositoryConfig);
    }

    public function setFormatFixure()
    {
        $this->_formatFixure =
            [
                'type'=>'jpg',
                'mimeType'=>'image/jpg',
                'extension'=>'jpg',
                'name'=>'jpg',
            ];
    }

    public function setResolverFactory()
    {
        $this->_resolverFactory = new FilesystemResolverFactory();
    }

    public function setStorageRepositoryConfig()
    {
        $this->_storageRepositoryConfig = new StorageRepositoryConfig($this->_testPathList);
    }

    public function setThumbnailFixure()
    {
        $this->_thumbnailFixure = [
            'targetPath'=> 'a target Path',
            'resolution'=> '72',
            'width'=> '123',
            'height'=>'213',
            'extension'=>'jpg',
            'mimeType'=>'image/jpg',
            'sourcePath'=>'a sourcepath',
            'uuid' => 'asdasdsad sadasdasdsadas'
        ];
    }

    /**
     * @return array
     */
    public function getThumbnailFixure(int $iterator) : Array
    {
        return  [
            'targetPath'=> 'a target Path' . $iterator,
            'resolution'=> '72',
            'width'=> '123' . $iterator,
            'height'=>'213' . $iterator,
            'extension'=>'jpg',
            'mimeType'=>'image/jpg',
            'sourcePath'=>'a sourcepath' . $iterator,
            'uuid' => 'asdasdsad sadasdasdsadas'. $iterator
        ];
    }

    public function setContainer()
    {
        $this->container = $this->_kernel->getContainer();
    }

    public function setKernel()
    {
        $this->_kernel = static::createKernel();
        $this->_kernel->boot();
    }

    public function setStoragePath()
    {
        $this->_storagePath = __DIR__ . '/../../../../storage';
    }

    public function setTestImages()
    {
        $fileinfos = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->_storagePath . '/assets')
        );


        /**
         * @var  $pathname
         * @var  $fileinfo \SplFileInfo
         */


        foreach($fileinfos as $pathname => $fileinfo)
        {
            $assetPath ='';

            if($fileinfo->getExtension()=="idlk") continue;

            if (!$fileinfo->isDir())
            {
                $assetPath = 'assets/' . $fileinfo->getPathInfo()->getBasename() . '/'. $fileinfo->getFileName();
            }
            else if($fileinfo->isFile())
            {
                $assetPath = 'assets/' . $fileinfo->getFileName();
            }

            $assetPath = str_replace("assets/assets","assets",$assetPath);

            if(!empty($assetPath))
            {
                $this->_testPathList[] = $assetPath;
            }
        }
    }

    public function setFlySystemItemMock()
    {
        $this->_flySystemItemMock =
            [
                'path'=>'apathonthe server',
                'basename'=>'thebasename',
                'size'=>23123123,
                'type'=>'jpg',
                'dirname'=>'adirname',
                'extension'=>'.jpg',
                'timestamp'=>12123123123,
                'filename'=>'afilename.jpg',
            ];
    }

    public function getFlySystemItemMock($i, $addition ='_highres')
    {
        return
            [
                'path'=>'apathonthe/path',
                'basename'=>'thebasename' . $i,
                'size'=> $i + 23123123,
                'type'=>'jpg',
                'dirname'=>'adirname' . $i,
                'extension'=>'.jpg',
                'timestamp'=>12123123123 + $i,
                'filename'=>'afilename' . $addition . '.jpg' . $i,
            ];
    }


    public function setStorageRepositoryFactory()
    {
        $this->_storageRepositoryFactory = new Factory($this->_storageRepositoryConfig);
    }


    public function setFormatToMimeTypeList()
    {
        $this->_formatToMimeTypeList = new FormatToMimeTypeList();
    }

    public function setMediaConverterConfig()
    {
        $this->_mediaConverterConfig = Yaml::parse(file_get_contents(__DIR__ . '/../../../../app/config/mediaconverter.yml'));
    }


    public function tearDown()
    {
        if ($this->_isBlackFireTracked)
        {
            $this->stopBlackFire($this->_probe);
        }
    }

    /**
     * @var string
     */
    protected $_storageItemUuid;

    /**
     * @var string
     */
    protected $_currentTempFilePath;
    /**
     * @var string
     */
    protected $_currentTargetStoragePath;

    /**
     *
     */
    public function setThumbnailJobFixure()
    {
        $this->_thumbnailJobFixure =
            [
                'box' => new Box(100,100),
                'mode' => 'mode',
                'currentTargetStoragePath'=>'pathtoasset',
                'currentTempFilePath' => 'currentTempFilePath',
                'storageItemUuid' => 'storageItemUuid'
            ];
    }



    public function setJobTicketFixure()
    {
        $imageMetadata = Filesystem::getCachedAdapter('storage')->getMetadata($this->_testPathList[0]);

        $storageItem = new StorageItem($imageMetadata);
        $jobTicketBuilder = new Builder($storageItem);
        $this->_jobTicketFixure = $jobTicketBuilder->getJobTicket();
    }

    public function getMetadataItemListMock()
    {
        return [new MetadataItem(['test'=>'testItem1']), new MetadataItem(['test'=>'testItem1'])];
    }

    /**
     *
     */
    public function setMetadataRepositoryConfig()
    {
        $this->_metadataRepositoryConfig = new RepositoryConfig(new Metadata($this->getMetadataItemListMock()));
    }

    /**
     * @return \Mittax\MediaConverterBundle\Repository\Metadata\Factory
     */
    public function getMetadataRepositoryFactory()
    {
        return new \Mittax\MediaConverterBundle\Repository\Metadata\Factory($this->_metadataRepositoryConfig);
    }

    public function getIndesignServerExportJPGMockResponse()
    {
        $mockIndesignServerResponse = new \stdClass();

        $mockIndesignServerResponse->clientEvent = 'indesignserver.lowres.created';

        $mockIndesignServerResponse->ticketId = 'asdasdasdasd';

        $mockIndesignServerResponse->urls = [];

        $mockIndesignServerResponse->status = 'ready';

        $additionalData = new \stdClass();

        $additionalData->Key='Document.ExportJPG.pageThumbnailPaths';

        $path = "\\\\vmware-host\\Shared Folders\\shared_storage\\export\\6e53364f7d34d75121f72807dfe4938e\\Document.ExportJPG_highres_10.jpg";

        $additionalData->Value= [$path,$path];

        $mockIndesignServerResponse->additionalData = [$additionalData];

        $mockIndesignServerResponse->errors = [];

        $ticket = new \stdClass();

        $ticket->id = uniqid();

        $command = new \stdClass();

        $command->uuid = uniqid();

        $command->version = 1.0;

        $command->extension = "indd";

        $ticket->commands = [$command];

        $mockIndesignServerResponse->ticket = $ticket;

        return $mockIndesignServerResponse;
    }

}