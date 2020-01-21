<?php


namespace App\Model\Request;

use JMS\Serializer\Annotation\Type;

class EventGoalRequestModel
{

    /**
     * @var int
     *
     * @Type("integer")
     */
    private $teamId;

    public function __construct(int $teamId)
    {
        $this->teamId = $teamId;
    }

    /**
     * @return int
     */
    public function getTeamId(): int
    {
        return $this->teamId;
    }
}
