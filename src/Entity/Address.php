<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $rue = null;



    /**
     * @var Collection<int, Trajet>
     */
    #[ORM\OneToMany(targetEntity: Trajet::class, mappedBy: 'Debut', orphanRemoval: true)]
    private Collection $Debut;

    /**
     * @var Collection<int, Trajet>
     */
    #[ORM\OneToMany(targetEntity: Trajet::class, mappedBy: 'Destination', orphanRemoval: true)]
    private Collection $Destiantion;



    public function __construct()
    {
        $this->Debut = new ArrayCollection();
        $this->Destiantion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }



    /**
     * @return Collection<int, Trajet>
     */
    public function getDebut(): Collection
    {
        return $this->Debut;
    }

    public function addDebut(Trajet $debut): static
    {
        if (!$this->Debut->contains($debut)) {
            $this->Debut->add($debut);
            $debut->setDebut($this);
        }

        return $this;
    }

    public function removeDebut(Trajet $debut): static
    {
        if ($this->Debut->removeElement($debut)) {
            // set the owning side to null (unless already changed)
            if ($debut->getDebut() === $this) {
                $debut->setDebut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Trajet>
     */
    public function getDestiantion(): Collection
    {
        return $this->Destiantion;
    }

    public function addDestiantion(Trajet $destiantion): static
    {
        if (!$this->Destiantion->contains($destiantion)) {
            $this->Destiantion->add($destiantion);
            $destiantion->setDestination($this);
        }

        return $this;
    }

    public function removeDestiantion(Trajet $destiantion): static
    {
        if ($this->Destiantion->removeElement($destiantion)) {
            // set the owning side to null (unless already changed)
            if ($destiantion->getDestination() === $this) {
                $destiantion->setDestination(null);
            }
        }

        return $this;
    }




}
