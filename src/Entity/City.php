<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

//#[Groups(['city_api'])]
#[ORM\Entity(repositoryClass: CityRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ce nom de ville existe déjà')]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['location_api'])]
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Entrez un nom de ville")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom de votre ville doit faire au moins 3 caractères", maxMessage: "Le nom de votre ville ne doit pas dépasser 255 caractères")]
    private ?string $name = null;

    #[Groups(['location_api'])]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Entrez un code postal")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le code postale doit faire au moins 3 caractères", maxMessage: "Le code postal ne doit pas dépasser 255 caractères")]
    private ?string $zipcode = null;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'city')]
    private Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
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

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setCity($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getCity() === $this) {
                $location->setCity(null);
            }
        }

        return $this;
    }
}
