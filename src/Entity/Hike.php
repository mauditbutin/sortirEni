<?php

namespace App\Entity;

use App\Repository\HikeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HikeRepository::class)]
class Hike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Entrez un titre")]
    #[Assert\Length(min: 5, max: 255, minMessage: "Votre titre doit faire au moins 5 caractères", maxMessage: "Votre titre ne doit pas dépasser 255 caractères")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Entrez une date pour l'évènement")]
    #[Assert\GreaterThanOrEqual(propertyPath: "dateSubscription", message: "La date de l'évènement doit être postérieure ou égale à la date limite des inscriptions")]
    #[Assert\GreaterThanOrEqual('today UTC', message: "La date doit être dans le futur")]
    private ?\DateTime $dateEvent = null;

    #[ORM\Column]
    #[Assert\Positive(message: "La durée de la randonnée doit être supérieure à 0 minutes")]
    #[Assert\NotBlank(message: "Entrez une durée")]
    #[Assert\Type(type: 'integer', message: "Entrez un chiffre")]
    private ?int $duration = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Entrez une date limite d'inscription")]
    #[Assert\LessThanOrEqual(propertyPath: "dateEvent", message: "La date limite d'inscription doit être antérieure ou égale à la date de début de l'évènement")]
    #[Assert\GreaterThanOrEqual('today UTC', message: "La date doit être dans le futur")]
    private ?\DateTime $dateSubscription = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le nombre de participants doit être supérieur à 0")]
    #[Assert\Type(type: 'integer', message: "Entrez un chiffre")]
    #[Assert\NotBlank(message: "Entrez un nombre limite de participants")]
    private ?int $nbMaxSubscription = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Entrez une description")]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\ManyToOne(inversedBy: 'hikes')]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'hikes')]
    #[Assert\NotNull(message: "Choisissez un niveau de difficulté")]
    private ?Difficulty $difficulty = null;

    #[ORM\ManyToOne(inversedBy: 'hikes')]
    #[Assert\NotNull(message: "Choisissez un lieu")]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'hikes')]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(inversedBy: 'plannedHikes')]
    private ?User $planner = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'participatedHikes')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateEvent(): ?\DateTime
    {
        return $this->dateEvent;
    }

    public function setDateEvent(?\DateTime $dateEvent): static
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateSubscription(): ?\DateTime
    {
        return $this->dateSubscription;
    }

    public function setDateSubscription(?\DateTime $dateSubscription): static
    {
        $this->dateSubscription = $dateSubscription;

        return $this;
    }

    public function getNbMaxSubscription(): ?int
    {
        return $this->nbMaxSubscription;
    }

    public function setNbMaxSubscription(?int $nbMaxSubscription): static
    {
        $this->nbMaxSubscription = $nbMaxSubscription;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

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

    public function getPlanner(): ?User
    {
        return $this->planner;
    }

    public function setPlanner(?User $planner): static
    {
        $this->planner = $planner;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipant(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
