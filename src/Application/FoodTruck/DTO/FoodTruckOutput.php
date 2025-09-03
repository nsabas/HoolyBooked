<?php

namespace App\Application\FoodTruck\DTO;

class FoodTruckOutput
{
    public function __construct(
        public string $name,
        public string $uid,
        public string $status,
        public string $description,
        public string $type
    ) {}
}
