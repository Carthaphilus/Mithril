<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="agenda")
 * @ORM\Entity(repositoryClass="App\Repository\AgendaRepository")
 */
class Agenda
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clepartage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="Evenement", mappedBy="agenda")
     */
    private $evenement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Acces", mappedBy="Agenda")
     */
    private $acces;

    public function __construct()
    {
        $this->evenement = new ArrayCollection();
        $this->acces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getClepartage(): ?string
    {
        return $this->clepartage;
    }

    public function setClepartage(?string $clepartage): self
    {
        $this->clepartage = $clepartage;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenement(): Collection
    {
        return $this->evenement;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenement->contains($evenement)) {
            $this->evenement[] = $evenement;
            $evenement->setAgenda($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenement->contains($evenement)) {
            $this->evenement->removeElement($evenement);
            // set the owning side to null (unless already changed)
            if ($evenement->getAgenda() === $this) {
                $evenement->setAgenda(null);
            }
        }

        return $this;
    }

    function getAcces() {
        return $this->acces;
    }

    function setAcces($acces) {
        $this->acces = $acces;
    }

}
