<?php

namespace App\Application\FoodTruck\Query;

class ListFoodTruckQuery
{
    public function __construct(
        public ?string $type = null
    ) {}

    public function getQueryParams(): array
    {
        return get_object_vars($this);
    }
}
