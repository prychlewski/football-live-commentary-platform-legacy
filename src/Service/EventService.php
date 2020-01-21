<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Team;
use App\Exception\EventNotFoundException;
use App\Exception\TeamDoesNotTakePartInEventException;
use App\Repository\EventRepository;

class EventService
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var TeamService
     */
    private $teamService;

    public function __construct(EventRepository $eventRepository, TeamService $teamService)
    {
        $this->eventRepository = $eventRepository;
        $this->teamService = $teamService;
    }

    public function createAndSaveEvent(int $hostTeamId, int $guestTeamId, \DateTime $date): Event
    {
        $hostTeam = $this->teamService->findTeam($hostTeamId);
        $guestTeam = $this->teamService->findTeam($guestTeamId);

        $event = new Event($hostTeam, $guestTeam, $date);

        $this->eventRepository->add($event);

        return $event;
    }

    public function update(int $id, int $hostTeamId, int $guestTeamId, \DateTime $date): Event
    {
        $event = $this->findEvent($id);

        $fieldUpdateMap = [
            'hostTeam'  => $this->teamService->findTeam($hostTeamId),
            'guestTeam' => $this->teamService->findTeam($guestTeamId),
            'date'      => $date,
        ];

        foreach ($fieldUpdateMap as $property => &$newValue) {
            $currentSetter = 'set' . ucwords($property);
            if (!method_exists($event, $currentSetter)) {
                continue;
            }

            $valueGetter = 'get' . ucwords($property);
            if (!$this->shouldUpdateProperty($event->$valueGetter(), $newValue)) {
                continue;
            }
            $event->$currentSetter($newValue);
        }
        unset($newValue);

        $this->eventRepository->update($event);

        return $event;
    }

    public function delete(int $id): void
    {
        $event = $this->findEvent($id);
        $this->eventRepository->delete($event);
    }

    public function findEvent(int $id): Event
    {
        $event = $this->eventRepository->findOneById($id);

        if (!$event) {
            throw new EventNotFoundException();
        }

        return $event;
    }

    public function scoreAGoal(int $id, int $teamId)
    {
        $event = $this->findEvent($id);

        $providedTeam = null;
        switch (true) {
            case $event->getGuestTeam()->getId() === $teamId:
                $providedTeam = 'guest';
                break;
            case $event->getHostTeam()->getId() === $teamId:
                $providedTeam = 'host';
                break;
            default:
                throw new TeamDoesNotTakePartInEventException();
        }

        $pointsSetter = sprintf('set%sPoints', ucwords($providedTeam));
        $pointsGetter = sprintf('get%sPoints', ucwords($providedTeam));
        if (!method_exists($event, $pointsSetter)) {
            throw new \BadMethodCallException('there is no method named: ' . $pointsSetter);
        }

        $score = $event->$pointsGetter();
        $event->$pointsSetter(++$score);

        $this->eventRepository->update($event);
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
