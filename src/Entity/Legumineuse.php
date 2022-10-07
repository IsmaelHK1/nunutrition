<?php

namespace App\Entity;

use App\Repository\LegumineuseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LegumineuseRepository::class)]
class Legumineuse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 22)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?int $Calories = null;

    #[ORM\Column]
    private ?int $Glucides = null;

    #[ORM\Column]
    private ?int $Proteines = null;

    #[ORM\Column]
    private ?int $Lipides = null;

    #[ORM\Column(length: 10)]
    private ?string $Categories = null;

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
}