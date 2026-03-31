<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ce nom de campus existe déjà')]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Entrez un nom de campus")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom de votre campus doit faire au moins 3 caractères", maxMessage: "Le nom de votre campus ne doit pas dépasser 255 caractères")]
    private ?string $name = null;

    /**
     * @var Collection<int, Hike>
     */
    #[ORM\OneToMany(targetEntity: Hike::class, mappedBy: 'campus')]
    private Collection $hikes;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'campus')]
    private Collection $users;

    public function __construct()
    {
        $this->hikes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Hike>
     */
    public function getHikes(): Collection
    {
        return $this->hikes;
    }

    public function addHike(Hike $hike): static
    {
        if (!$this->hikes->contains($hike)) {
            $this->hikes->add($hike);
            $hike->setCampus($this);
        }

        return $this;
    }

    public function removeHike(Hike $hike): static
    {
        if ($this->hikes->removeElement($hike)) {
            // set the owning side to null (unless already changed)
            if ($hike->getCampus() === $this) {
                $hike->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCampus($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCampus() === $this) {
                $user->setCampus(null);
            }
        }

        return $this;
    }
}
