<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\API\Booking;

use App\Application\Booking\Command\UpdateStatusBookingCommand;
use App\Application\Booking\Handler\UpdateStatusHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class UpdateStatusBookingController extends AbstractController
{
    public function __construct(
        private readonly UpdateStatusHandler $updateStatusHandler
    ) {}

    #[OA\Patch(
        tags: ['Booking']
    )]
    #[Route('/api/reservations/{uid}', name: 'api_update_status_booking', methods: [Request::METHOD_PATCH])]
    public function index(string $uid, #[MapRequestPayload] UpdateStatusBookingCommand $updateStatusBookingCommand): JsonResponse
    {
        $updateStatusBookingCommand->bookingUid = $uid;
        return $this->json(
            $this->updateStatusHandler->handle($updateStatusBookingCommand),
            Response::HTTP_OK
        );
    }
}
