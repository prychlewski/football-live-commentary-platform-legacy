<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="FootballMatch")
     * @ORM\JoinColumn(nullable=false)
     */
    private $footballMatch;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function __construct(FootballMatch $footballMatch, string $content, \DateTime $date)
    {
        $this->footballMatch = $footballMatch;
        $this->date = $date;
        $this->content = $content;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFootballMatch(): ?FootballMatch
    {
        return $this->footballMatch;
    }

    public function setFootballMatch(?FootballMatch $footballMatch): self
    {
        $this->footballMatch = $footballMatch;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
