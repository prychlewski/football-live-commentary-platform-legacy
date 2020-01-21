<?php


namespace App\Model\Request;

use JMS\Serializer\Annotation\Type;

class EventCommentRequestModel
{
    /**
     * @var string
     *
     * @Type("string")
     */
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
