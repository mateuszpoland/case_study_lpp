<?php
declare(strict_types=1);

namespace Lpp\Lpp\Service\Factory;
use Lpp\Lpp\Service\BrandServiceInterface;
use Lpp\Lpp\Service\ItemServiceInterface;
use Lpp\Lpp\Service\ItemPriceSortedBrandService;
use Lpp\Lpp\Service\UnorderedBrandService;

class BrandServiceFactory
{
    public static function getBrandService(ItemServiceInterface $itemService, $args = null): BrandServiceInterface
    {
        switch ($args) {
            case 'item_price_ordered':
                return new ItemPriceSortedBrandService($itemService);

            default:
                return new UnorderedBrandService($itemService);
        }
    }
}