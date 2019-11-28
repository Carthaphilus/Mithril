<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 */
class Evenement
{    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeb;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieux;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    /*
     * @ORM\Column(type="array", nullable=true)
     */
//    private $taches = [];

    /**
     * @ORM\ManyToOne(targetEntity="Agenda", inversedBy="evenement")
     */
    private $agenda;


    public function getId(): ?int
    {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getLieux(): ?string
    {
        return $this->lieux;
    }

    public function setLieux(?string $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

//    public function getTaches(): ?array
//    {
//        return $this->taches;
//    }

//    public function setTaches(?array $taches): self
//    {
//        $this->taches = $taches;
//
//        return $this;
//    }

    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }
    
    function getDateDeb() {
        return $this->dateDeb;
    }

    function getDateFin() {
        return $this->dateFin;
    }

    function setDateDeb($dateDeb) {
        $this->dateDeb = $dateDeb;
    }

    function setDateFin($dateFin) {
        $this->dateFin = $dateFin;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }


}
