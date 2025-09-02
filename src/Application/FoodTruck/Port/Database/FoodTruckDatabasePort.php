<?php

namespace App\Application\FoodTruck\Port\Database;

use App\Domain\Model\FoodTruck;
use Doctrine\Common\Collections\Collection;

interface FoodTruckDatabasePort
{
    public function save(FoodTruck $foodTruck, bool $flush = false): void;

    public function findOneByUid(string $uid): ?FoodTruck;

    /**
     * @param array $queryParams
     * @return FoodTruck[]
     */
        public function findByType(array $queryParams): array;
}
