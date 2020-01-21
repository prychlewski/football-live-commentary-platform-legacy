<?php


namespace App\Model\Request;

use JMS\Serializer\Annotation\Type;

class TeamRequestModel
{
    /**
     * @var string
     *
     * @Type("string")
     */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
