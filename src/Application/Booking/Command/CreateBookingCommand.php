<?php

namespace App\Application\Booking\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBookingCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $email,
        #[Assert\GreaterThan('tomorrow')]
        public \DateTimeImmutable $startAt,
        #[Assert\NotBlank]
        public string $foodTruckUid,
        #[Assert\NotBlank]
        public string $slotUid
    ) {}
}
