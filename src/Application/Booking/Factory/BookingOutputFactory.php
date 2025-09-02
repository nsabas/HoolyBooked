<?php

namespace App\Application\Booking\Factory;

use App\Application\Booking\DTO\BookingOutput;
use App\Domain\Model\Booking;

class BookingOutputFactory
{
    public static function createFromModel(Booking $booking): BookingOutput
    {
        return new BookingOutput(
            name: $booking->getName(),
            status: $booking->getStatus(),
            uid: $booking->getUid(),
            start: $booking->getStartAt()->format(DATE_ISO8601_EXPANDED),
            end: $booking->getEndAt()->format(DATE_ISO8601_EXPANDED),
            foodTruckName: $booking->getFoodTruck()->getName()
        );
    }

    /**
     * @param Booking[] $bookings
     * @return BookingOutput[]
     */
    public static function createArrayOutput(array $bookings): array
    {
        $outputs = [];

        foreach ($bookings as $booking) {
            $outputs[] = self::createFromModel($booking);
        }

        return $outputs;
    }
}
