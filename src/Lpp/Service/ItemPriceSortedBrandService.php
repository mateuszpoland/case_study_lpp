<?php
declare(strict_types=1);

namespace Lpp\Lpp\Service;

use Lpp\Lpp\Entity\Item;
use Lpp\Lpp\Service\ItemService;
use Lpp\Lpp\Service\BrandService;

/**
* sorts Items in ascending direction - from the cheapest to most expensive items
*/
class ItemPriceSortedBrandService extends BrandService
{
    /**
     * @param string $collectionName Name of the collection to search for.
     *
     * @return \Lpp\Lpp\Entity\Item[]
     */
    public function getItemsForCollection($collectionName): array
    {
        $unsorted = parent::getItemsForCollection($collectionName);

        $sorted = usort($unsorted, function($prevIndex, $nextIndex){
            $key1 = array_keys($prevIndex)[0];
            $key2 = array_keys($nextIndex)[0];
            $lowestPrev = [];
            $lowestNext = [];
            foreach($prevIndex[$key1]->prices as $price) {
                $lowestPrev[] = $price->priceInEuro;
            } 
            foreach($nextIndex[$key2]->prices as $price) {
                $lowestNext[] = $price->priceInEuro;
            } 
            return min($lowestPrev) <=> min($lowestNext);
        });

        return $unsorted;
    }
}