<?php

namespace App\Service;

use App\Entity\Team;
use App\Exception\TeamNotFoundException;
use App\Repository\TeamRepository;

class TeamService
{
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function createAndSaveTeam(string $teamName)
    {
        $team = new Team($teamName);

        $this->teamRepository->add($team);

        return $team;
    }

    public function update(int $id, string $teamName): Team
    {
        $team = $this->findTeam($id);
        $team->setName($teamName);

        $this->teamRepository->update($team);

        return $team;
    }

    public function delete(int $id): void
    {
        $team = $this->findTeam($id);

        $this->teamRepository->delete($team);
    }

    public function findTeam(int $id)
    {
        $team = $this->teamRepository->findOneById($id);
        if (!$team) {
            throw new TeamNotFoundException();
        }

        return $team;
    }
}
