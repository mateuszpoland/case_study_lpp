<?php
declare(strict_types=1);

namespace Lpp\Lpp\Service;

class CollectionService 
{
    public const COLLECTION_SIGNATURE_ID = 'id';
    public const COLLECTION_SIGNATURE_NAME = 'collection';
    public const COLLECTION_SIGNATURE_BRANDS = 'brands';

    public const COLLECTION_SIGNATURES = [
        self::COLLECTION_SIGNATURE_ID,
        self::COLLECTION_SIGNATURE_NAME,
        self::COLLECTION_SIGNATURE_BRANDS
    ];

     /** @var array */
     private $collectionRawData = [
        self::COLLECTION_SIGNATURE_ID => null,
        self::COLLECTION_SIGNATURE_NAME => null,
        self::COLLECTION_SIGNATURE_BRANDS => null
    ];

    /** @var string */
    private $dataSourceFilePath;

    public function __construct(string $dataSourceFilePath)
    {
        $this->dataSourceFilePath = $dataSourceFilePath;
        $this->deconstructCollectionData();
    }

    public function getCollectionData(?string $key)
    {
        if($key) {
            if(!array_key_exists($key, $this->collectionRawData)){
                throw new \Exception(sprintf('Key [%s] does not exist in datasource.', $key));
            }
            return $this->collectionRawData[$key];
        }
        return $this->collectionRawData;
    }

    private function deconstructCollectionData()
    {
        $fileArray = json_decode($this->openFile(), true);
        foreach (self::COLLECTION_SIGNATURES as $signature) {
            if (array_key_exists($signature, $fileArray)){
                $this->collectionRawData[$signature] = $fileArray[$signature];
            }
        }
    }

    private function openFile(): string
    {
        $fileHandle = @fopen($this->dataSourceFilePath, 'r');
        if(!$fileHandle) {
            throw new \Exception('cannot open data source');
        }
        return file_get_contents($this->dataSourceFilePath);
    }
}