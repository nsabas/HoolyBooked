<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Application\FoodTruck\Port\Database\FoodTruckDatabasePort;
use App\Domain\Model\FoodTruck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FoodTruck>
 */
class FoodTruckRepository extends ServiceEntityRepository implements FoodTruckDatabasePort
{
    public function __construct(private readonly ManagerRegistry $registry,)
    {
        parent::__construct($this->registry, FoodTruck::class);
    }

    public function save(FoodTruck $foodTruck, bool $flush = false): void
    {
        $this->getEntityManager()->persist($foodTruck);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByUid(string $uid): ?FoodTruck
    {
        return $this->findOneBy(['uid' => $uid]);
    }

    /**
     * @param array $queryParams
     * @return FoodTruck[]
     */
    public function findByType(array $queryParams): array
    {
        return $this->findBy([]);
    }
}
