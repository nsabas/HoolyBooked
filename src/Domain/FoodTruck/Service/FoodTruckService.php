<?php

namespace App\Domain\FoodTruck\Service;

use App\Application\Booking\Port\Database\BookingDatabasePort;
use App\Domain\Model\Campus;
use App\Domain\Model\FoodTruck;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FoodTruckService
{
    public function __construct(
        private BookingDatabasePort $bookingDatabasePort
    ) {}

    public function isAvailableForBooking(
        FoodTruck $foodTruck,
        Campus $campus,
        \DateTimeImmutable $startAt,
        \DateTimeImmutable $endAt
    ): bool {
        $this->isAllowedToBeBooked($foodTruck, $campus, $startAt);

        return !$this->bookingDatabasePort->hasOverlappingBooking($foodTruck, $startAt, $endAt);
    }

    private function isAllowedToBeBooked(FoodTruck $foodTruck, Campus $campus, \DateTimeInterface $date): void
    {
        $startOfWeek = $date->modify('monday this week');
        $endOfWeek = $date->modify('sunday this week');

        if ($this->bookingDatabasePort->hasBookingBetweenFor($foodTruck, $campus, $startOfWeek, $endOfWeek)) {
            throw new BadRequestHttpException('Food truck has been already booked this week on this campus');
        }
    }
}
