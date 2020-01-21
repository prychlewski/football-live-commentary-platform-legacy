<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Common\Persistence\ManagerRegistry;

class TeamRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function add(Team $team): void
    {
        $this->genericAdd($team);
    }

    public function update(Team $team)
    {
        $this->genericUpdate($team);
    }

    public function delete(Team $team)
    {
        $this->genericDelete($team);
    }
}
