<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\API\Booking;

use App\Application\Booking\Handler\ListBookingHandler;
use App\Application\Booking\Query\ListBookingQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ListBookingController extends AbstractController
{
    public function __construct(
        public ListBookingHandler $listBookingHandler
    ) {}

    #[Route('/api/reservations', name: 'api_list_reservations', methods: [Request::METHOD_GET])]
    public function __invoke(ListBookingQuery $listBookingCommand): JsonResponse
    {
        return $this->json(
            $this->listBookingHandler->handle($listBookingCommand)
        );
    }
}
