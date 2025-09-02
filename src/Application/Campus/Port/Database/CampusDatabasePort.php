<?php

namespace App\Application\Campus\Port\Database;

use App\Domain\Model\Campus;

interface CampusDatabasePort
{
    public function save(Campus $campus, bool $flush = false): void;
}
