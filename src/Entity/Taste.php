<?php

namespace App\Entity;

use App\Repository\TasteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TasteRepository::class)]
class Taste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tastes')]
    private ?Movie $movie = null;

    #[ORM\ManyToOne(inversedBy: 'tastes')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $tasteStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isTasteStatus(): ?bool
    {
        return $this->tasteStatus;
    }

    public function setTasteStatus(?bool $tasteStatus): self
    {
        $this->tasteStatus = $tasteStatus;

        return $this;
    }
}
