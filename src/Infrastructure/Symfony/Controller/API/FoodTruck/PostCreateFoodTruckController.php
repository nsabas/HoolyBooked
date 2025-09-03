<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\API\FoodTruck;

use App\Application\FoodTruck\Command\CreateFoodTruckCommand;
use App\Application\FoodTruck\Handler\CreateFoodTruckHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class PostCreateFoodTruckController extends AbstractController
{
    public function __construct(
        private CreateFoodTruckHandler $createFoodTruckHandler
    ) {}

    #[OA\Post(
        tags: ['FoodTruck']
    )]
    #[Route('/api/food_trucks', name: 'api_create_food_truck', methods: [Request::METHOD_POST])]
    public function __invoke(#[MapRequestPayload] CreateFoodTruckCommand $command): Response
    {
        return $this->json(
            $this->createFoodTruckHandler->handle($command),
            Response::HTTP_CREATED
        );
    }
}
