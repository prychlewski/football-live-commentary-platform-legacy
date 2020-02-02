<?php

namespace App\Service;

use App\Entity\FootballMatch;
use App\Entity\Team;
use App\Exception\FootballMatchNotFoundException;
use App\Exception\TeamDoesNotTakePartInMatchException;
use App\Repository\FootballMatchRepository;

class FootballMatchService
{
    /**
     * @var FootballMatchRepository
     */
    private $footballMatchRepository;

    /**
     * @var TeamService
     */
    private $teamService;

    public function __construct(FootballMatchRepository $footballMatchRepository, TeamService $teamService)
    {
        $this->footballMatchRepository = $footballMatchRepository;
        $this->teamService = $teamService;
    }

    public function createAndSaveFootballMatch(int $hostTeamId, int $guestTeamId, \DateTime $date): FootballMatch
    {
        $hostTeam = $this->teamService->findTeam($hostTeamId);
        $guestTeam = $this->teamService->findTeam($guestTeamId);

        $footballMatch = new FootballMatch($hostTeam, $guestTeam, $date);

        $this->footballMatchRepository->add($footballMatch);

        return $footballMatch;
    }

    public function update(int $id, int $hostTeamId, int $guestTeamId, \DateTime $date): FootballMatch
    {
        $footballMatch = $this->findFootballMatch($id);

        $fieldUpdateMap = [
            'hostTeam'  => $this->teamService->findTeam($hostTeamId),
            'guestTeam' => $this->teamService->findTeam($guestTeamId),
            'date'      => $date,
        ];

        foreach ($fieldUpdateMap as $property => &$newValue) {
            $currentSetter = 'set' . ucwords($property);
            if (!method_exists($footballMatch, $currentSetter)) {
                continue;
            }

            $valueGetter = 'get' . ucwords($property);
            if (!$this->shouldUpdateProperty($footballMatch->$valueGetter(), $newValue)) {
                continue;
            }
            $footballMatch->$currentSetter($newValue);
        }
        unset($newValue);

        $this->footballMatchRepository->update($footballMatch);

        return $footballMatch;
    }

    public function delete(int $id): void
    {
        $footballMatch = $this->findFootballMatch($id);
        $this->footballMatchRepository->delete($footballMatch);
    }

    public function findFootballMatch(int $id): FootballMatch
    {
        $footballMatch = $this->footballMatchRepository->findOneById($id);

        if (!$footballMatch) {
            throw new FootballMatchNotFoundException();
        }

        return $footballMatch;
    }

    public function scoreAGoal(int $id, int $teamId)
    {
        $footballMatch = $this->findFootballMatch($id);

        $providedTeam = null;
        switch (true) {
            case $footballMatch->getGuestTeam()->getId() === $teamId:
                $providedTeam = 'guest';
                break;
            case $footballMatch->getHostTeam()->getId() === $teamId:
                $providedTeam = 'host';
                break;
            default:
                throw new TeamDoesNotTakePartInMatchException();
        }

        $pointsSetter = sprintf('set%sPoints', ucwords($providedTeam));
        $pointsGetter = sprintf('get%sPoints', ucwords($providedTeam));
        if (!method_exists($footballMatch, $pointsSetter)) {
            throw new \BadMethodCallException('there is no method named: ' . $pointsSetter);
        }

        $score = $footballMatch->$pointsGetter();
        $footballMatch->$pointsSetter(++$score);

        $this->footballMatchRepository->update($footballMatch);
    }

    private function shouldUpdateProperty($currentValue, $newValue): bool
    {
        switch (true) {
            case $currentValue instanceof Team:
                return $currentValue->getId() !== $newValue->getId();
            case $currentValue instanceof \DateTime:
                return $currentValue !== $newValue;
            default:
                return false;
        }
    }
}
