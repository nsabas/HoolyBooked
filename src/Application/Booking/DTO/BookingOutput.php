<?php

namespace App\Application\Booking\DTO;

class BookingOutput
{
    public function __construct(
        public string $name,
        public string $status,
        public string $uid,
        public string $start,
        public string $end,
        public string $foodTruckName
    ) {}
}
