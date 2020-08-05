<?php
declare(strict_types=1);

namespace Lpp\Lpp\Service;

use Exception;
use Lpp\Lpp\Entity\Brand;
use Lpp\Lpp\Entity\Item;
use Lpp\Lpp\Entity\ValueObject\ItemUrl;
use Lpp\Lpp\Entity\Price;
use Lpp\Lpp\Service\CollectionService;

class ItemService implements ItemServiceInterface
{
    /** @var string */
    private $dataSourceFilePath;

    /** @var CollectionService */
    private $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    public function getResultForCollectionId($collectionId)
    {
        $this->checkIfCorrectCollectionIdRequested($collectionId);
        return $this->processBrands();
    }

    private function processBrands(): array 
    {
        $brandsData = $this->collectionService
                        ->getCollectionData(CollectionService::COLLECTION_SIGNATURE_BRANDS);
        if(!empty($brandsData)){
            return array_map(function($brandNode) {
                $brand = new Brand();
                $brand->brand = $brandNode['name'];
                $brand->description = $brandNode['description'];
                $brand->items = $this->processItems($brandNode['items']);
                return $brand;
            }, $brandsData);
        }
        return $brandsData;
    }

    private function processItems(array $items): array
    {
        return array_map(function($itemElem){
            $item = new Item();
            $item->name = $itemElem['name'];
            $item->url = (new ItemUrl($itemElem['url'], $itemElem['name']))->__toString();
            $item->prices = $this->processPrices($itemElem['prices']);
            return $item;
        },$items);
    }

    private function processPrices(array $prices)
    {
        return array_map(function($priceElem){
            $price = new Price();
            $price->description = $priceElem['description'];
            $price->priceInEuro = $priceElem['priceInEuro'];
            $price->arrivalDate = new \DateTime($priceElem['arrival']);
            $price->dueDate = new \DateTime($priceElem['due']);
            return $price;
        }, $prices);
    }

    private function checkIfCorrectCollectionIdRequested(int $collectionId): void
    {
        if($collectionId !== $this->collectionService->getCollectionData(CollectionService::COLLECTION_SIGNATURE_ID)) {
            throw new  \InvalidArgumentException(sprintf('Invalid collection id requested. Id : [%s]. Not found in data source.', $collectionId));
        }
    }
}