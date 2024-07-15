<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trajetreservation')]
    private ?Trajet $idtrajet = null;

    #[ORM\ManyToOne(inversedBy: 'reservationuser')]
    private ?User $iduser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdtrajet(): ?Trajet
    {
        return $this->idtrajet;
    }

    public function setIdtrajet(?Trajet $idtrajet): static
    {
        $this->idtrajet = $idtrajet;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }
}
