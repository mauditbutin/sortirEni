<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    /**
     * @var Collection<int, Hike>
     */
    #[ORM\OneToMany(targetEntity: Hike::class, mappedBy: 'planner')]
    private Collection $plannedHikes;

    /**
     * @var Collection<int, Hike>
     */
    #[ORM\ManyToMany(targetEntity: Hike::class, mappedBy: 'participants')]
    private Collection $participatedHikes;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Campus $campus = null;

    public function __construct()
    {
        $this->plannedHikes = new ArrayCollection();
        $this->participatedHikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Hike>
     */
    public function getHikes(): Collection
    {
        return $this->plannedHikes;
    }

    public function addHike(Hike $hike): static
    {
        if (!$this->plannedHikes->contains($hike)) {
            $this->plannedHikes->add($hike);
            $hike->setPlanner($this);
        }

        return $this;
    }

    public function removeHike(Hike $hike): static
    {
        if ($this->hikes->removeElement($hike)) {
            // set the owning side to null (unless already changed)
            if ($hike->getPlanner() === $this) {
                $hike->setPlanner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hike>
     */
    public function getParticipatedHikes(): Collection
    {
        return $this->participatedHikes;
    }

    public function addParticipatedHike(Hike $participatedHike): static
    {
        if (!$this->participatedHikes->contains($participatedHike)) {
            $this->participatedHikes->add($participatedHike);
            $participatedHike->addParticipant($this);
        }

        return $this;
    }

    public function removeParticipatedHike(Hike $participatedHike): static
    {
        if ($this->participatedHikes->removeElement($participatedHike)) {
            $participatedHike->removeParticipant($this);
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }
}
