<?php

namespace App\Infrastructure\Symfony\Controller\API\FoodTruck;

use App\Application\FoodTruck\Handler\ListFoodTruckHandler;
use App\Application\FoodTruck\Query\ListFoodTruckQuery;
use App\Domain\Model\FoodTruck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class ListFoodTruckController extends AbstractController
{
    public function __construct(
        private ListFoodTruckHandler $listFoodTruckHandler
    ) {}

    #[OA\Get(
        tags: ['FoodTruck']
    )]
    #[Route('/api/food_trucks', name: 'api_list_food_truck', methods: [Request::METHOD_GET])]
    public function __invoke(ListFoodTruckQuery $query): JsonResponse
    {
        return $this->json(
            $this->listFoodTruckHandler->handle($query)
        );
    }
}
