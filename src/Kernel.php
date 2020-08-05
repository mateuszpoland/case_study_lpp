<?php
namespace Lpp;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Lpp\Lpp\Service\ItemService;
use Lpp\Lpp\Service\CollectionService;
use Lpp\Lpp\Service\BrandService;
use Lpp\Lpp\Service\Factory\BrandServiceFactory;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle()
        ];
    }

    protected function configureContainer(ContainerConfigurator $c): void
    {
        $c->extension('framework', [
            'secret' => 'S0ME_SECRET'
        ]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('home', '/')->controller([$this, 'indexAction']);
        $routes->add('first', '/getResult/{collectionId}')->controller([$this, 'firstAndSecondTask']);
        $routes->add('third', '/getItems/{collectionName}')->controller([$this, 'thirdTask']);
    }

    public function indexAction(): Response
    {
        return new Response(
            '<html>
                <body>
                    <h2>Zadanie rekrutacyjne LPP</h2>
                    <br/>
                </body>
            </html>'
        );
    }

    public function firstAndSecondTask(int $collectionId): JsonResponse
    {
        try{
            $collectionService = $this->getCollectionService($collectionId);
            $itemService = new ItemService($collectionService);
            $result = $itemService->getResultForCollectionId($collectionId);
            return new JsonResponse($result, 200);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function thirdTask(string $collectionName): JsonResponse
    {
        try {
            $collectionService = $this->getCollectionService();
            $itemService = new ItemService($collectionService);
            $brandService = BrandServiceFactory::getBrandService($itemService, 'item_price_ordered');
            $result = $brandService->getItemsForCollection($collectionName);
            return new JsonResponse($result, 200);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    private function getCollectionService(): CollectionService
    {
        $dataSourcePath = './data/1315475.json';
        return new CollectionService($dataSourcePath);
    }
}
