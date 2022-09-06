<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Taste::class)]
    private Collection $tastes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mate = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'user')]
    private Collection $movies;

    public function __construct()
    {
        $this->tastes = new ArrayCollection();
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $taste->setUser($this);
        }

        return $this;
    }

    public function removeTaste(Taste $taste): self
    {
        if ($this->tastes->removeElement($taste)) {
            // set the owning side to null (unless already changed)
            if ($taste->getUser() === $this) {
                $taste->setUser(null);
            }
        }

        return $this;
    }

    public function getMate(): ?string
    {
        return $this->mate;
    }

    public function setMate(?string $mate): self
    {
        $this->mate = $mate;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addUser($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeUser($this);
        }

        return $this;
    }
}
