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
     * @ORM\ManyToMany(targetEntity="App\Entity\news", inversedBy="categories")
     */
    private $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
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
     * @return Collection|news[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(news $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
        }

        return $this;
    }

    public function removeNews(news $news): self
    {
        if ($this->news->contains($news)) {
            $this->news->removeElement($news);
        }

        return $this;
    }
}
