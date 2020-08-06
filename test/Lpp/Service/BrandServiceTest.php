<?php
declare(strict_types=1);

namespace Lpp\Test\Lpp\Service;

use PHPUnit\Framework\TestCase;
use Lpp\Lpp\Service\UnorderedBrandService;
use Lpp\Lpp\Service\ItemPriceSortedBrandService;
use Lpp\Lpp\Service\ItemService;
use Lpp\Lpp\Entity\Brand;
use Lpp\Lpp\Entity\Item;
use Lpp\Lpp\Entity\Price;

class BrandServiceTest extends TestCase
{
    /** @var ItemService|Mock */
    private $itemServiceMock;

    public function setUp(): void
    {
        $this->itemServiceMock = $this->createMock(ItemService::class);
    }

    public function getBrands(): array
    {
        $mockBrand = new Brand();
        $mockBrand->brand = 'XYZ';
        $mockBrand->description = 'Summer Collection';
        for($i = 0; $i < 4; $i++) {
            $item = new Item();
            $item->name = 'name' . $i;
            $item->url = 'http://example.com';
            for ($j = 0; $j < 5; $j++) {
                $price = new Price();
                $price->description = 'price description' . $j;
                // prices will be sorted descendingly - 800, 750, 700, 650, 600
                $price->priceInEuro = 800 - ($j * 50);
                $item->prices[] = $price;
            }
            $mockBrand->items[] = $item;
        }

        return [
            [
                [
                    $mockBrand
                ]
            ]
        ];
    }

    /** @dataProvider getBrands */
    public function testRetrievesItemListFromBrandList($brandData): void
    {
        $brandService = $this->getUnorderedBrandService();
        $this->itemServiceMock->expects($this->once())
                              ->method('getResultForCollectionId')
                              ->willReturn($brandData);

        $result = $brandService->getItemsForCollection('winter');
        foreach($result as $key => $itemList) {
            $this->assertCount(4, $itemList);
        }
    }

    public function testThrowsExceptionWhenInvalidMappingProvided(): void
    {
        $brandService = $this->getUnorderedBrandService();
        $brandService->setCollectionNameToIdMapping([]);
        $this->expectException(\InvalidArgumentException::class);

        $brandService->getItemsForCollection('winter');
    }

    private function getUnorderedBrandService(): UnorderedBrandService
    {
        return new UnorderedBrandService($this->itemServiceMock);
    }
}