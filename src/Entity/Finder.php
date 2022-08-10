<?php

namespace App\Entity;

use App\Repository\FinderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinderRepository::class)]
class Finder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'finders')]
    private ?Watchlist $movie = null;

    #[ORM\ManyToOne(inversedBy: 'finders')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $likeStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovie(): ?Watchlist
    {
        return $this->movie;
    }

    public function setMovie(?Watchlist $movie): self
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

    public function isLikeStatus(): ?bool
    {
        return $this->likeStatus;
    }

    public function setLikeStatus(?bool $likeStatus): self
    {
        $this->likeStatus = $likeStatus;

        return $this;
    }
}
