<?php

namespace App\Domain\FoodTruck\Service;

use App\Application\Booking\Port\Database\BookingDatabasePort;
use App\Domain\Model\FoodTruck;

class FoodTruckService
{
    public function __construct(
        private BookingDatabasePort $bookingDatabasePort
    ) {}

    public function isAvailableForBooking(
        FoodTruck $foodTruck,
        \DateTimeImmutable $startAt,
        \DateTimeImmutable $endAt
    ): bool {
        return $this->bookingDatabasePort->hasOverlappingBooking($foodTruck, $startAt, $endAt);
    }
}
