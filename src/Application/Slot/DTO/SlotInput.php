<?php

namespace App\Application\Slot\DTO;

class SlotInput
{
    public function __construct(
        public string $name,
        public string $fullAddress,
        public string $street,
        public string $city,
        public string $state,
        public string $zipCode,
        public string $country
    ) {}
}
