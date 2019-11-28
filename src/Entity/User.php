<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"mail"}, message="There is already an account with this mail")
 */
class User implements UserInterface
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mdp;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Acces", mappedBy="User")
     */
    private $acces;

    public function __construct()
    {
        $this->acces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDatenaissance(): ?string
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?string $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->getMdp();
    }

    public function getRoles() {
        return array("ROLE_USER");;
    }

    public function getSalt() {
        
    }

    public function getUsername(): string {
        return $this->getMail();
    }

    /**
     * @return Collection|Acces[]
     */
    public function getAcces(): Collection
    {
        return $this->acces;
    }

    public function addAcce(Acces $acce): self
    {
        if (!$this->acces->contains($acce)) {
            $this->acces[] = $acce;
            $acce->setUser($this);
        }

        return $this;
    }

    public function removeAcce(Acces $acce): self
    {
        if ($this->acces->contains($acce)) {
            $this->acces->removeElement($acce);
            // set the owning side to null (unless already changed)
            if ($acce->getUser() === $this) {
                $acce->setUser(null);
            }
        }

        return $this;
    }

}
