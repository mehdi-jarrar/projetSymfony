<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dishes", mappedBy="user")
     */
    private $dishes;

    public function __construct()
    {
        parent::__construct();
        $this->dishes = new ArrayCollection();
        $this->roles = array('ROLE_USER');
        // your own logic
    }

    /**
     * @return Collection|Dishes[]
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDishes(Dishes $dishes): self
    {
        if (!$this->dishes->contains($dishes)) {
            $this->dishes[] = $dishes;
            $dishes->addUser($this);
        }

        return $this;
    }

    public function removeDishes(Dishes $dishes): self
    {
        if ($this->dishes->contains($dishes)) {
            $this->dishes->removeElement($dishes);
            $dishes->removeUser($this);
        }

        return $this;
    }
}