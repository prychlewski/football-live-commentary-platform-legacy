<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventComment;
use Doctrine\Common\Persistence\ManagerRegistry;

class EventCommentRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventComment::class);
    }

    public function add(EventComment $eventComment): void
    {
        $this->genericAdd($eventComment);
    }

    public function update(EventComment $eventComment): void
    {
        $this->genericUpdate($eventComment);
    }

    public function delete(EventComment $eventComment): void
    {
        $this->genericDelete($eventComment);
    }

    public function findByEvent(Event $event)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $query = $queryBuilder->select('ec.id, ec.content, ec.date')
            ->from(EventComment::class, 'ec')
            ->where('ec.event = :event')
            ->setParameter('event', $event)
            ->getQuery();

        return $query->execute();
    }
}
