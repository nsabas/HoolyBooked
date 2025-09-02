<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Application\Campus\Port\Database\CampusDatabasePort;
use App\Domain\Model\Campus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CampusRepository extends ServiceEntityRepository implements CampusDatabasePort
{
    public function __construct(private readonly ManagerRegistry $registry,)
    {
        parent::__construct($this->registry, Campus::class);
    }

    public function save(Campus $campus, bool $flush = false): void
    {
        $this->getEntityManager()->persist($campus);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
