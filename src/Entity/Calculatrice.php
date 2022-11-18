<?php

namespace App\Entity;

use App\Repository\CalculatriceRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CalculatriceRepository::class)]
class Calculatrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getAllCalculatrice', 'getCalculatrice'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['getAllCalculatrice', 'getCalculatrice'])]
    private ?int $poid = null;

    #[ORM\Column]
    #[Groups(['getAllCalculatrice', 'getCalculatrice'])]
    private ?int $height = null;

    #[ORM\Column]
    private ?int $total_cal = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getAllCalculatrice', 'getCalculatrice'])]
    private ?user $user_id = null;

    #[ORM\Column]
    #[Groups(['getAllCalculatrice', 'getCalculatrice'])]
    private ?int $age = null;

    #[ORM\Column(length: 4)]
    private ?string $status = null;

    #[ORM\Column(length: 20)]
    private ?string $cat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoid(): ?int
    {
        return $this->poid;
    }

    public function setPoid(int $poid): self
    {
        $this->poid = $poid;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getTotalCal(): ?int
    {
        return $this->total_cal;
    }

    public function setTotalCal(int $total_cal): self
    {
        $this->total_cal = $total_cal;

        return $this;
    }

    public function getUserId(): ?user
    {
        return $this->user_id;
    }

    public function setUserId(user $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }


    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @param int $poid
     * @param int $height
     * @param int $age
     * @param int $id
     * @param string $cat
     * @return Calculatrice
     */
    public function setAllCalculatrice(int $poid, int $height,int $age,user $user, string $cat): self
    {

        if($cat === 'PDM'){
         $this->total_cal = 13.707 * $poid + 492.3 * $height - 6.673 * $age + 77.607;
        }else if($cat === 'seche'){
            $this->total_cal = 10 * $poid + 6.25 * $height - 5 * $age - 161;
        }else{
            $this->total_cal = 0;
        }

        $this->poid = $poid;
        $this->height = $height;
        $this->age = $age;
        $this->user_id = $user;
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

    public function getCat(): ?string
    {
        return $this->cat;
    }

    public function setCat(string $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

}
