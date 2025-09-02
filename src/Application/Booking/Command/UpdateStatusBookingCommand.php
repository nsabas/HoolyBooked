<?php

namespace App\Application\Booking\Command;

use App\Domain\Booking\Enum\BookingStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateStatusBookingCommand
{
    public string $bookingUid;

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [BookingStatusEnum::class, 'all'])]
        public string $newStatus
    ) {}
}
