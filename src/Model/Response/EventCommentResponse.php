<?php

namespace App\Model\Response;

use DateTime;
use JMS\Serializer\Annotation\Type;

final class EventCommentResponse
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
     * @var string
     *
     * @Type("string")
     */
    private $content;

    public function __construct(
        int $id,
        string $content,
        DateTime $date
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
