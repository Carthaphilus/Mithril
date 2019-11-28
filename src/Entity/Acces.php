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
     * @ORM\OneToMany(targetEntity="App\Entity\Agenda", mappedBy="acces")
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

    /**
     * @return Collection|Agenda[]
     */
    public function getAgenda(): Collection
    {
        return $this->Agenda;
    }

    public function addAgenda(Agenda $agenda): self
    {
        if (!$this->Agenda->contains($agenda)) {
            $this->Agenda[] = $agenda;
            $agenda->setAcces($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): self
    {
        if ($this->Agenda->contains($agenda)) {
            $this->Agenda->removeElement($agenda);
            // set the owning side to null (unless already changed)
            if ($agenda->getAcces() === $this) {
                $agenda->setAcces(null);
            }
        }

        return $this;
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
