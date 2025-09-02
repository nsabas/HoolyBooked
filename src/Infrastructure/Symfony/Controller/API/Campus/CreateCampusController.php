<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller\API\Campus;

use App\Application\Campus\Command\CreateCampusCommand;
use App\Application\Campus\Handler\CreateCampusHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCampusController extends AbstractController
{
    public function __construct(
        private CreateCampusHandler $createCampusHandler
    ) {}

    #[Route('/api/campus', name: 'api_campus_create', methods: [Request::METHOD_POST])]
    public function __invoke(#[MapRequestPayload] CreateCampusCommand $createCampusCommand): JsonResponse
    {
        return $this->json(
            $this->createCampusHandler->handle($createCampusCommand),
            Response::HTTP_CREATED
        );
    }
}
