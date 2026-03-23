<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $label = null;

    /**
     * @var Collection<int, Hike>
     */
    #[ORM\OneToMany(targetEntity: Hike::class, mappedBy: 'status')]
    private Collection $hikes;

    public function __construct()
    {
        $this->hikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

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
            $hike->setStatus($this);
        }

        return $this;
    }

    public function removeHike(Hike $hike): static
    {
        if ($this->hikes->removeElement($hike)) {
            // set the owning side to null (unless already changed)
            if ($hike->getStatus() === $this) {
                $hike->setStatus(null);
            }
        }

        return $this;
    }
}
