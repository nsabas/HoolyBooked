<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\API\Booking;

use App\Application\Booking\Handler\ListBookingHandler;
use App\Application\Booking\Query\ListBookingQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class ListBookingController extends AbstractController
{
    public function __construct(
        public ListBookingHandler $listBookingHandler
    ) {}

    #[Route('/api/reservations', name: 'api_list_reservations', methods: [Request::METHOD_GET])]
    #[OA\Get(
        tags: ['Booking'],
        parameters: [
            new OA\Parameter(
                name: 'campusUid',
                description: 'Filtrer par campus',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'date',
                description: 'Filtrer par date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ]
    )]
    public function __invoke(ListBookingQuery $listBookingCommand): JsonResponse
    {
        return $this->json(
            $this->listBookingHandler->handle($listBookingCommand)
        );
    }
}
