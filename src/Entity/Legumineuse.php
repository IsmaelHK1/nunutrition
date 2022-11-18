<?php

namespace App\Entity;

use Assert\NotBlank;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LegumineuseRepository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LegumineuseRepository::class)]
class Legumineuse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 22)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private ?string $Name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    private ?int $Calories = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    private ?int $Glucides = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    private ?int $Proteines = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    private ?int $Lipides = null;

    #[ORM\Column(length: 10)]
    #[Assert\Choice(choices : ['PDM', 'seche','PDM & seche'], message: 'Le type doit être PDM ou seche ou PDM & seche')]
    private ?string $Categories = null;

    #[ORM\Column(length: 4)]
    #[Assert\Choice(choices: ['on', 'off'], message: 'Please enter a valid value : on , off')]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->Calories;
    }

    public function setCalories(int $Calories): self
    {
        $this->Calories = $Calories;

        return $this;
    }

    public function getGlucides(): ?int
    {
        return $this->Glucides;
    }

    public function setGlucides(int $Glucides): self
    {
        $this->Glucides = $Glucides;

        return $this;
    }

    public function getProteines(): ?int
    {
        return $this->Proteines;
    }

    public function setProteines(int $Proteines): self
    {
        $this->Proteines = $Proteines;

        return $this;
    }

    public function getLipides(): ?int
    {
        return $this->Lipides;
    }

    public function setLipides(int $Lipides): self
    {
        $this->Lipides = $Lipides;

        return $this;
    }

    public function getCategories(): ?string
    {
        return $this->Categories;
    }

    public function setCategories(string $Categories): self
    {
        $this->Categories = $Categories;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
