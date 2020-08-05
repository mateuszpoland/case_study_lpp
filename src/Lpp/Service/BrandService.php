<?php
declare(strict_types=1);

namespace Lpp\Lpp\Service;
use Lpp\Lpp\Service\BrandServiceInterface;

abstract class BrandService implements BrandServiceInterface
{
    /** @var itemService */
    private $itemService;

    /**
     * Maps from collection name to the id for the item service.
     *  
     * @var []
     */
    private $collectionNameToIdMapping = [
        "winter" => 1315475
    ];

    public function __construct(ItemServiceInterface $itemService)
    {
        $this->itemService = $itemService;
    }
    /**
     * @param string $collectionName Name of the collection to search for.
     *
     * @return \Lpp\Lpp\Entity\Item[]
     */
     public function getItemsForCollection($collectionName): array
     {
         if (empty($this->collectionNameToIdMapping[$collectionName])) {
             throw new \InvalidArgumentException(sprintf('Provided collection name [%s] is not mapped.', $collectionName));
         }
         $brands = $this->itemService->getResultForCollectionId($this->collectionNameToIdMapping[$collectionName]);
         return array_map(function($brand) {
             return $brand->items;
         }, $brands);
     }

      /**
     * This is supposed to be used for testing purposes.
     * You should avoid replacing the item service at runtime.
     *
     * @param \Lpp\Service\ItemServiceInterface $itemService
     *
     * @return void
     */
    public function setItemService(ItemServiceInterface $itemService) {
        $this->itemService = $itemService;
    }
}