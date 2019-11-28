<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccesRepository")
 */
class Acces
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="acces")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agenda", inversedBy="acces", fetch="EAGER")
     */
    private $Agenda;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Droit;

    public function __construct()
    {
        $this->Agenda = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    function getAgenda() {
        return $this->Agenda;
    }

    function setAgenda($Agenda) {
        $this->Agenda = $Agenda;
    }
    
    public function getDroit(): ?bool
    {
        return $this->Droit;
    }

    public function setDroit(bool $Droit): self
    {
        $this->Droit = $Droit;

        return $this;
    }
}
