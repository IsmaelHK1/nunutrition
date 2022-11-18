<?php

namespace App\Entity;

use App\Repository\FruitLegumeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Hateoas\Configuration\Annotation as Hateoas;



// /**
//  * @Hateoas\Relation(
//  *     "self",
//  *      href = @Hateoas\Route(
//  *         "app_fruit_and_legume.get",
//  *          parameters = {
//  *           "idFruitLegume" = "expr(object.getId())"
//  *         },
//  *    ),
//  *        exclusion = @Hateoas\Exclusion(groups = {"getAllFruitLegume"})    
//  *  )
//  */

#[ORM\Entity(repositoryClass: FruitLegumeRepository::class)]
class FruitLegume
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?string $Name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?int $Glucides = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?int $Proteines = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?int $Lipides = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La quantité est obligatoire')]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?int $Calories = null;

    #[ORM\Column(length: 10)]
    #[Assert\Choice(choices : ['PDM', 'seche','PDM & seche'], message: 'Le type doit être PDM ou seche ou PDM & seche')]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?string $Categories = null;

    #[ORM\Column(length: 4)]
    #[Assert\Choice(choices: ['on', 'off'], message: 'Please enter a valid value : on , off')]
    #[Groups(['getAllFruitLegume', 'getFruitLegume'])]
    private ?string $Status = null;

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

    public function getCalories(): ?int
    {
        return $this->Calories;
    }

    public function setCalories(int $Calories): self
    {
        $this->Calories = $Calories;

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
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
}
