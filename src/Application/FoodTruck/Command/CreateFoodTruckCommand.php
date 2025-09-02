<?php

namespace App\Application\FoodTruck\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateFoodTruckCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        public string $description,
        public string $type
    )
    {}
}
