<?php
declare(strict_types=1);

namespace Lpp\Test\Lpp\Service;

use PHPUnit\Framework\TestCase;
use Lpp\Lpp\Service\ItemService;
use Lpp\Lpp\Service\CollectionService;

class ItemServiceTest extends TestCase
{
    /** @var CollectionService|Mock */
    private $collectionServiceMock;

    public function setUp(): void
    {
        $this->collectionServiceMock = $this->createMock(CollectionService::class);
    }

    public function brandsData(): array
    {
        return [
            [
                [ 0 => [
                    'name' => 'Reserved',
                    'description' => 'New winter collection',
                    'items' => [
                        1000 => [
                            'name' => 'jacket',
                            'url'  => 'https://www.reserved.pl/jacket',
                            'prices' => [
                                1001 => [
                                    'description' => 'Initial price',
                                    'priceInEuro' => 250,
                                    'arrival'     => '2017-01-03',
                                    'due'         => '2017-01-20'
                                ],
                                1002 => [
                                    'description' => 'Clearance price',
                                    'priceInEuro' => 150,
                                    'arrival'     => '2017-03-01',
                                    'due'         => '2017-04-01'
                                ]
                            ]
                        ],

                    ]   
                ]
                ],
                1315475   
            ]
        ];
    }

    /**
    * @dataProvider brandsData
    */
    public function testProcessesBrands(array $brandsData, int $collectionId): void
    {
        $itemService = $this->getItemService();
   
        $this->collectionServiceMock->expects($this->at(0))
                                    ->method('getCollectionData')
                                    ->with(CollectionService::COLLECTION_SIGNATURE_ID)
                                    ->willReturn($collectionId);
        
        $this->collectionServiceMock->expects($this->at(1))
                                    ->method('getCollectionData')
                                    ->with(CollectionService::COLLECTION_SIGNATURE_BRANDS)
                                    ->willReturn($brandsData);

        $processed = $itemService->getResultForCollectionId($collectionId);
        for($i = 0; $i < count($processed); $i++) {
            $this->assertEquals($brandsData[$i]['name'], $processed[$i]->brand);
            $this->assertEquals($brandsData[$i]['description'], $processed[$i]->description);
            // etc...
        }
    }

    public function testThrowsExceptionWhenReqeustsInvalidCollectionId(): void
    {
        $requestedCollectionId = 123;
        $collectionIdInDataSource = 456;
        $itemService = $this->getItemService();
        $this->collectionServiceMock->expects($this->once())
                            ->method('getCollectionData')
                            ->with(CollectionService::COLLECTION_SIGNATURE_ID)
                            ->willReturn($collectionIdInDataSource);
        
        $this->expectException(\InvalidArgumentException::class);
        
        $itemService->getResultForCollectionId($requestedCollectionId);
    }

    private function getItemService(): ItemService
    {
        return new ItemService($this->collectionServiceMock);
    }
}