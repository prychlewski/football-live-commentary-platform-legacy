<?php

namespace App\Model\Response;

use DateTime;
use JMS\Serializer\Annotation\Type;

final class EventResponse
{
    /**
     * @var int
     *
     * @Type("integer")
     */
    private $id;

    /**
     * @var DateTime
     *
     * @Type("DateTime")
     */
    private $date;

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
     */
    private $guestTeamId;

    /**
     * @var int
     *
     * @Type("integer")
     */
    private $hostPoints;

    /**
     * @var int
     *
     * @Type("integer")
     */
    private $guestPoints;

    public function __construct(
        int $id,
        int $hostTeamId,
        int $guestTeamId,
        DateTime $date,
        int $hostPoints = 0,
        int $guestPoints = 0
    ) {
        $this->id = $id;
        $this->hostTeamId = $hostTeamId;
        $this->guestTeamId = $guestTeamId;
        $this->date = $date;
        $this->hostPoints = $hostPoints;
        $this->guestPoints = $guestPoints;
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getHostPoints(): int
    {
        return $this->hostPoints;
    }

    /**
     * @return int
     */
    public function getGuestPoints(): int
    {
        return $this->guestPoints;
    }
}
