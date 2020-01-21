<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Team
     */
    private $hostTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Team
     */
    private $guestTeam;

    /**
     * @ORM\Column(type="integer")
     */
    private $hostPoints = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $guestPoints = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct(Team $hostTeam, Team $guestTeam, \DateTime $date)
    {
        $this->hostTeam = $hostTeam;
        $this->guestTeam = $guestTeam;
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHostTeam(): Team
    {
        return $this->hostTeam;
    }

    public function getHostTeamId(): int
    {
        return $this->hostTeam->getId();
    }

    public function setHostTeam(Team $hostTeam): self
    {
        $this->hostTeam = $hostTeam;

        return $this;
    }

    public function getGuestTeam(): Team
    {
        return $this->guestTeam;
    }

    public function getGuestTeamId(): int
    {
        return $this->guestTeam->getId();
    }

    public function setGuestTeam(Team $guestTeam): self
    {
        $this->guestTeam = $guestTeam;

        return $this;
    }

    public function getHostPoints(): ?int
    {
        return $this->hostPoints;
    }

    public function setHostPoints(int $hostPoints): self
    {
        $this->hostPoints = $hostPoints;

        return $this;
    }

    public function getGuestPoints(): ?int
    {
        return $this->guestPoints;
    }

    public function setGuestPoints(int $guestPoints): self
    {
        $this->guestPoints = $guestPoints;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
