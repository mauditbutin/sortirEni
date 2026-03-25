<?php

namespace App\Form\Model;

use App\Entity\Campus;
use App\Repository\Form\Model\HikerFilterDTORepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HikerFilterDTORepository::class)]
class HikeFilterDTO
{

    private ?int $id = null;

    private ?string $name = null;

    private ?Campus $campus = null;


    private ?\DateTime $dateStart = null;


    private ?\DateTime $dateEnd = null;


    private ?bool $organise = null;


    private ?bool $participe = null;


    private ?bool $participePas = null;


    private ?bool $terminee = null;

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

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTime $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTime $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function isOrganise(): ?bool
    {
        return $this->organise;
    }

    public function setOrganise(?string $organise): static
    {
        $this->organise = $organise;

        return $this;
    }

    public function isParticipe(): ?bool
    {
        return $this->participe;
    }

    public function setParticipe(bool $participe): static
    {
        $this->participe = $participe;

        return $this;
    }

    public function isParticipePas(): ?bool
    {
        return $this->participePas;
    }

    public function setParticipePas(bool $participePas): static
    {
        $this->participePas = $participePas;

        return $this;
    }

    public function isTerminee(): ?bool
    {
        return $this->terminee;
    }

    public function setTerminee(bool $terminee): static
    {
        $this->terminee = $terminee;

        return $this;
    }
}
