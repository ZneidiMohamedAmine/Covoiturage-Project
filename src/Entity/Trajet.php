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
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'Debut')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $Debut = null;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'Destination')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $Destination = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $SeatsAvailable = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $SeatsOccupied = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $Price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $Time = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'trajets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner_id = null;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'trajet')]
    private Collection $addresses;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function getOwnerId(): ?User
    {
        return $this->owner_id;
    }

    public function setOwnerId(?User $owner_id): static
    {
        $this->owner_id = $owner_id;

        return $this;
    }
}
