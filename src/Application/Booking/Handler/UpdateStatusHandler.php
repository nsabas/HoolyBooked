<?php

namespace App\Application\Booking\Handler;

use App\Application\Booking\Command\UpdateStatusBookingCommand;
use App\Application\Booking\DTO\BookingOutput;
use App\Application\Booking\Factory\BookingOutputFactory;
use App\Application\Booking\Port\Database\BookingDatabasePort;
use App\Domain\Model\Booking;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateStatusHandler
{
    public function __construct(
        private BookingDatabasePort $bookingDatabasePort
    ) {}

    public function handle(UpdateStatusBookingCommand $bookingCommand): BookingOutput
    {
        if (!$booking = $this->bookingDatabasePort->findByUid($bookingCommand->bookingUid)) {
            throw new NotFoundHttpException('Booking not found');
        }

        $booking->setStatus($bookingCommand->newStatus);
        $this->bookingDatabasePort->save($booking, true);

        return BookingOutputFactory::createFromModel($booking);;
    }
}
