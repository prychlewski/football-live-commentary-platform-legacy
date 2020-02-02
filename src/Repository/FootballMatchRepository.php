<?php

namespace App\Repository;

use App\Entity\FootballMatch;
use Doctrine\Common\Persistence\ManagerRegistry;

class FootballMatchRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FootballMatch::class);
    }

    public function add(FootballMatch $footballMatch): void
    {
        $this->genericAdd($footballMatch);
    }

    public function update(FootballMatch $footballMatch): void
    {
        $this->genericUpdate($footballMatch);
    }

    public function delete(FootballMatch $footballMatch): void
    {
        $this->genericDelete($footballMatch);
    }
}
