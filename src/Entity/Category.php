<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dishes", inversedBy="categories")
     */
    private $dishes;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|dishes[]
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDishes(dishes $dishes): self
    {
        if (!$this->dishes->contains($dishes)) {
            $this->dishes[] = $dishes;
        }

        return $this;
    }

    public function removeDishes(dishes $dishes): self
    {
        if ($this->dishes->contains($dishes)) {
            $this->dishes->removeElement($dishes);
        }

        return $this;
    }
}
