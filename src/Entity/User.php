<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $FirstName = null;

    #[ORM\Column(length: 255)]
    private ?string $LastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $BirthDate = null;

    #[ORM\ManyToOne(inversedBy: 'AddressUser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $Address = null;

    #[ORM\Column(length: 255)]
    private ?string $Gender = null;

    #[ORM\Column(nullable: true)]
    private ?bool $DriverLicense = null;

    #[ORM\Column(length: 255)]
    private ?string $Role = null;

    #[ORM\Column(length: 255)]
    private ?string $PhotoAdress = null;

    #[ORM\Column(length: 255)]
    private ?string $Password = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?comment $CommentID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getFirstNmae(): ?string
    {
        return $this->FirstName;
    }
    #U8j90;40

    public function setFirstName(string $nom): static
    {
        $this->FirstName = $nom;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): static
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->BirthDate;
    }

    public function setBirthDate(\DateTimeInterface $BirthDate): static
    {
        $this->BirthDate = $BirthDate;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->Address;
    }

    public function setAddress(?Address $Address): static
    {
        $this->Address = $Address;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function isDriverLicense(): ?bool
    {
        return $this->DriverLicense;
    }

    public function setDriverLicense(?bool $DriverLicense): static
    {
        $this->DriverLicense = $DriverLicense;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): static
    {
        $this->Role = $Role;

        return $this;
    }

    public function getPhotoAdress(): ?string
    {
        return $this->PhotoAdress;
    }

    public function setPhotoAdress(string $PhotoAdress): static
    {
        $this->PhotoAdress = $PhotoAdress;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getCommentID(): ?comment
    {
        return $this->CommentID;
    }

    public function setCommentID(comment $CommentID): static
    {
        $this->CommentID = $CommentID;

        return $this;
    }
}
