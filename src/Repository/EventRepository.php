<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Common\Persistence\ManagerRegistry;

class EventRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $event): void
    {
        $this->genericAdd($event);
    }

    public function update(Event $event): void
    {
        $this->genericUpdate($event);
    }

    public function delete(Event $event): void
    {
        $this->genericDelete($event);
    }
}
