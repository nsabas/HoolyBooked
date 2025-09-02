<?php

namespace App\Application\Booking\Handler;

use App\Application\Booking\Factory\BookingOutputFactory;
use App\Application\Booking\Port\Database\BookingDatabasePort;
use App\Application\Booking\Query\ListBookingQuery;
use App\Domain\Model\Booking;

class ListBookingHandler
{
    public function __construct(
        private BookingDatabasePort $bookingDatabasePort
    ) {}

    /**
     * @param ListBookingQuery $command
     * @return Booking[]
     */
    public function handle(ListBookingQuery $command): array
    {
        return BookingOutputFactory::createArrayOutput(
            $this->bookingDatabasePort->findBookingByDate(
                $command->date,
                $command->campusUid
            )
        );
    }
}
