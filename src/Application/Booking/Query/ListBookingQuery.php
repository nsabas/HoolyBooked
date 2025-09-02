<?php

namespace App\Application\Booking\Query;

class ListBookingQuery
{
    public function __construct(
        public ?string $campusUid = null,
        public ?\DateTimeImmutable $date = null
    ) {}
}
