<?php

namespace App\Model\Response;

use App\Entity\Team;
use DateTime;
use JMS\Serializer\Annotation\Type;

final class EventTeamResponse
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime
     *
     * @Type("DateTime")
     */
    private $date;

    public function __construct(EventResponse $eventView, Team $hostTeam, Team $guestTeam)
    {
        $this->id = $eventView->getId();
        $this->date = $eventView->getDate();
        $this->hostTeam = $hostTeam;
        $this->guestTeam = $guestTeam;
    }
}
