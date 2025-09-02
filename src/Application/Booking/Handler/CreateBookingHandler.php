<?php

namespace App\Application\Booking\Handler;

use App\Application\Booking\Command\CreateBookingCommand;
use App\Application\Booking\DTO\BookingOutput;
use App\Application\Booking\Factory\BookingOutputFactory;
use App\Application\Booking\Port\Database\BookingDatabasePort;
use App\Application\FoodTruck\Port\Database\FoodTruckDatabasePort;
use App\Application\Slot\Port\Database\SlotDatabasePort;
use App\Domain\Booking\Enum\BookingStatusEnum;
use App\Domain\FoodTruck\Service\FoodTruckService;
use App\Domain\Model\Booking;
use App\Domain\Slot\Service\SlotService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class CreateBookingHandler
{
    public function __construct(
        private BookingDatabasePort $bookingDatabasePort,
        private SlotDatabasePort $slotDatabasePort,
        private SlotService $slotService,
        private FoodTruckDatabasePort $foodTruckDatabasePort,
        private FoodTruckService $foodTruckService
    ) {}

    public function handle(CreateBookingCommand $command): BookingOutput
    {
        if (!$foodTruck = $this->foodTruckDatabasePort->findOneByUid($command->foodTruckUid)) {
            throw new NotFoundHttpException('Food truck not found');
        }

        if (!$slot = $this->slotDatabasePort->getSlotByUid($command->slotUid)) {
            throw new NotFoundHttpException('Slot not found');
        }

        if ($this->slotService->isSlotAvailable($slot, $command->startAt)) {
            throw new BadRequestHttpException('Slot not available for booking');
        }

        if ($this->foodTruckService->isAvailableForBooking($foodTruck, $command->startAt, $command->endAt)) {
            throw new BadRequestHttpException('Food truck is already booked');
        }

        $booking = (new Booking)
            ->setName($command->name)
            ->setEmail($command->email)
            ->setUid(Uuid::v4())
            ->setStatus(BookingStatusEnum::confirmed->name)
            ->setStartAt($command->startAt)
            ->setEndAt($command->endAt)
            ->setFoodTruck($foodTruck)
            ->setSlot($slot)
        ;

        $this->bookingDatabasePort->save($booking, true);

        return BookingOutputFactory::createFromModel($booking);
    }
}
