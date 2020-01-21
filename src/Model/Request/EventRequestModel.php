<?php


namespace App\Model\Request;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;

class EventRequestModel
{

    /**
     * @var int
     *
     * @Type("integer")
     */
    private $hostTeamId;

    /**
     * @var int
     *
     * @Type("integer")
     *
     */
    private $guestTeamId;

    /**
     * @var DateTime
     *
     * @Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $date;

    public function __construct(int $hostTeamId, int $guestTeamId, DateTime $date)
    {
        $this->hostTeamId = $hostTeamId;
        $this->guestTeamId = $guestTeamId;
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getHostTeamId(): int
    {
        return $this->hostTeamId;
    }

    /**
     * @return int
     */
    public function getGuestTeamId(): int
    {
        return $this->guestTeamId;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }
}
