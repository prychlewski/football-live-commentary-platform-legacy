<?php

namespace App\Service;

use App\Entity\EventComment;
use App\Exception\EventCommentNotFoundException;
use App\Repository\EventCommentRepository;

class EventCommentService
{
    /**
     * @var EventCommentRepository
     */
    private $eventCommentRepository;

    /**
     * @var EventService
     */
    private $eventService;

    public function __construct(
        EventCommentRepository $eventCommentRepository,
        EventService $eventService
    ) {
        $this->eventCommentRepository = $eventCommentRepository;
        $this->eventService = $eventService;
    }

    public function createAndSaveEventComment(int $eventId, string $comment): EventComment
    {
        $event = $this->eventService->findEvent($eventId);
        $eventComment = new EventComment($event, $comment, new \DateTime());

        $this->eventCommentRepository->add($eventComment);

        return $eventComment;
    }

    public function update(int $eventCommentId, string $comment): EventComment
    {
        /** @var EventComment $eventComment */
        $eventComment = $this->findEventComment($eventCommentId);
        $eventComment->setContent($comment);

        $this->eventCommentRepository->update($eventComment);

        return $eventComment;
    }

    public function delete(int $eventCommentId): void
    {
        $eventComment = $this->findEventComment($eventCommentId);
        $this->eventCommentRepository->delete($eventComment);
    }

    public function findEventComments(int $eventId): array
    {
        $event = $this->eventService->findEvent($eventId);

        return $this->eventCommentRepository->findByEvent($event);
    }

    public function findEventComment(int $eventCommentId)
    {
        $eventComment = $this->eventCommentRepository->findOneById($eventCommentId);
        if (!$eventComment) {
            throw new EventCommentNotFoundException();
        }

        return $eventComment;
    }
}
