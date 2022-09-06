<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbnail = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    #[ORM\Column(length: 255)]
    private ?string $director = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tagline = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $studio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $originalTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $banner = 'https://creativecloud.adobe.com/content/dam/2015/8/article-marquee/article-marquee7.jpg';

    #[ORM\Column(length: 255)]
    private ?string $plexLink = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Taste::class)]
    private Collection $tastes;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'movies')]
    private Collection $user;

    #[ORM\Column(length: 255)]
    private ?string $ratingKey = null;

    public function __construct()
    {
        $this->tastes = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }

    public function setTagline(string $tagline): self
    {
        $this->tagline = $tagline;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStudio(): ?string
    {
        return $this->studio;
    }

    public function setStudio(string $studio): self
    {
        $this->studio = $studio;

        return $this;
    }

    public function getOriginalTitle(): ?string
    {
        return $this->originalTitle;
    }

    public function setOriginalTitle(string $originalTitle): self
    {
        $this->originalTitle = $originalTitle;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function getPlexLink(): ?string
    {
        return $this->plexLink;
    }

    public function setPlexLink(string $plexLink): self
    {
        $this->plexLink = $plexLink;

        return $this;
    }

    /**
     * @return Collection<int, Taste>
     */
    public function getTastes(): Collection
    {
        return $this->tastes;
    }

    public function addTaste(Taste $taste): self
    {
        if (!$this->tastes->contains($taste)) {
            $this->tastes->add($taste);
            $taste->setMovie($this);
        }

        return $this;
    }

    public function removeTaste(Taste $taste): self
    {
        if ($this->tastes->removeElement($taste)) {
            // set the owning side to null (unless already changed)
            if ($taste->getMovie() === $this) {
                $taste->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getRatingKey(): ?string
    {
        return $this->ratingKey;
    }

    public function setRatingKey(string $ratingKey): self
    {
        $this->ratingKey = $ratingKey;

        return $this;
    }
}
