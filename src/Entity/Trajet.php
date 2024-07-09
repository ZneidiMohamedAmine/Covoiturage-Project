<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrajetRepository::class)]
class Trajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Debut')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $Debut = null;

    #[ORM\ManyToOne(inversedBy: 'Destiantion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $Destination = null;

    #[ORM\Column(nullable: true)]
    private ?int $SeatsAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?int $SeatsOccupied = null;

    #[ORM\Column(nullable: true)]
    private ?int $Price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $Time = null;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'trajet')]
    

    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Address>
     */

    public function getDebut(): ?Address
    {
        return $this->Debut;
    }

    public function setDebut(?Address $Debut): static
    {
        $this->Debut = $Debut;

        return $this;
    }

    public function getDestination(): ?Address
    {
        return $this->Destination;
    }

    public function setDestination(?Address $Destination): static
    {
        $this->Destination = $Destination;

        return $this;
    }

    public function getSeatsAvailable(): ?int
    {
        return $this->SeatsAvailable;
    }

    public function setSeatsAvailable(?int $SeatsAvailable): static
    {
        $this->SeatsAvailable = $SeatsAvailable;

        return $this;
    }

    public function getSeatsOccupied(): ?int
    {
        return $this->SeatsOccupied;
    }

    public function setSeatsOccupied(?int $SeatsOccupied): static
    {
        $this->SeatsOccupied = $SeatsOccupied;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(?int $Price): static
    {
        $this->Price = $Price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->Time;
    }

    public function setTime(\DateTimeInterface $Time): static
    {
        $this->Time = $Time;

        return $this;
    }

}
