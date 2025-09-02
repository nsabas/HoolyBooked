<?php

namespace App\Infrastructure\Symfony\Controller\API\Booking;

use App\Application\Booking\Command\CreateBookingCommand;
use App\Application\Booking\Handler\CreateBookingHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateBookingController extends AbstractController
{
    public function __construct(
        private CreateBookingHandler $createBookingHandler
    ) {}

    #[Route('/api/reservations', name: 'api_booking_create', methods: [Request::METHOD_POST])]
    public function __invoke(#[MapRequestPayload] CreateBookingCommand $createBookingCommand): JsonResponse
    {
        return $this->json(
            $this->createBookingHandler->handle($createBookingCommand),
            Response::HTTP_CREATED
        );
    }
}
