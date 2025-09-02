<?php

namespace App\Application\FoodTruck\Factory;

use App\Application\FoodTruck\DTO\FoodTruckOutput;
use App\Domain\Model\FoodTruck;

class FoodTruckDTOFactory
{
    public static function createFromFoodTruck(FoodTruck $foodTruck): FoodTruckOutput
    {
        return new FoodTruckOutput(
            name: $foodTruck->getName(),
            status: $foodTruck->getStatus(),
            description: $foodTruck->getDescription(),
            type: $foodTruck->getType()
        );
    }

    /**
     * @param FoodTruck[] $data
     * @return FoodTruckOutput[]
     */
    public static function createFromArray(array $data): array
    {
        $outputs = [];

        foreach ($data as $outputData) {
            $outputs[] = self::createFromFoodTruck($outputData);
        }

        return $outputs;
    }
}
