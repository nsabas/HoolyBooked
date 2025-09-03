<?php

namespace App\Application\Booking\Port\Database;

use App\Domain\Model\Booking;
use App\Domain\Model\Campus;
use App\Domain\Model\FoodTruck;

interface BookingDatabasePort
{
    public function save(Booking $foodTruck, bool $flush = false): void;

    public function findByUid(string $uid): ?Booking;

    public function hasOverlappingBooking(FoodTruck $foodTruck, \DateTimeImmutable $start, \DateTimeImmutable $end): bool;

    /**
     * @param \DateTimeImmutable|null $date
     * @return Booking[]
     */
    public function findBookingByDate(\DateTimeImmutable $date = null, string $campusUid = null): array;

    /**
     * @param \DateTimeImmutable $date
     * @return Booking[]
     */
    public function getBookingStartAt(\DateTimeImmutable $date): array;

    public function hasBookingBetweenFor(FoodTruck $foodTruck, Campus $campus, \DateTimeImmutable $start, \DateTimeImmutable $end): bool;
}
