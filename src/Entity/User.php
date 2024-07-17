<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $FirstName = null;

    #[ORM\Column(length: 255)]
    private ?string $LastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $BirthDate = null;

    #[ORM\Column(length: 255)]
    private ?string $Gender = null;

    #[ORM\Column(length: 255)]
    private ?string $CIN = null;

    #[ORM\Column]
    private ?bool $DriverLisence = null;

    #[ORM\Column(length: 255)]
    private ?string $photoAdress = null;

    #[ORM\Column(length: 255)]
    private ?string $Address = null;

    /**
     * @var Collection<int, Trajet>
     */
    #[ORM\OneToMany(targetEntity: Trajet::class, mappedBy: 'owner_id')]
    private Collection $userTrajet;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'iduser')]
    private Collection $reservationuser;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'commenterId')]
    private Collection $commentmade;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'commentedId')]
    private Collection $commentgiven;

    public function __construct()
    {
        $this->userTrajet = new ArrayCollection();
        $this->reservationuser = new ArrayCollection();
        $this->commentmade = new ArrayCollection();
        $this->commentgiven = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): static
    {
        $this->FirstName = $FirstName;

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

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(string $CIN): static
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function isDriverLisence(): ?bool
    {
        return $this->DriverLisence;
    }

    public function setDriverLisence(bool $DriverLisence): static
    {
        $this->DriverLisence = $DriverLisence;

        return $this;
    }

    public function getPhotoAdress(): ?string
    {
        return $this->photoAdress;
    }

    public function setPhotoAdress(string $photoAdress): static
    {
        $this->photoAdress = $photoAdress;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): static
    {
        $this->Address = $Address;

        return $this;
    }

    /**
     * @return Collection<int, Trajet>
     */
    public function getUserTrajet(): Collection
    {
        return $this->userTrajet;
    }

    public function addUserTrajet(Trajet $userTrajet): static
    {
        if (!$this->userTrajet->contains($userTrajet)) {
            $this->userTrajet->add($userTrajet);
            $userTrajet->setOwnerId($this);
        }

        return $this;
    }

    public function removeUserTrajet(Trajet $userTrajet): static
    {
        if ($this->userTrajet->removeElement($userTrajet)) {
            // set the owning side to null (unless already changed)
            if ($userTrajet->getOwnerId() === $this) {
                $userTrajet->setOwnerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservationuser(): Collection
    {
        return $this->reservationuser;
    }

    public function addReservationuser(Reservation $reservationuser): static
    {
        if (!$this->reservationuser->contains($reservationuser)) {
            $this->reservationuser->add($reservationuser);
            $reservationuser->setIduser($this);
        }

        return $this;
    }

    public function removeReservationuser(Reservation $reservationuser): static
    {
        if ($this->reservationuser->removeElement($reservationuser)) {
            // set the owning side to null (unless already changed)
            if ($reservationuser->getIduser() === $this) {
                $reservationuser->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentmade(): Collection
    {
        return $this->commentmade;
    }

    public function addCommentmade(Comment $commentmade): static
    {
        if (!$this->commentmade->contains($commentmade)) {
            $this->commentmade->add($commentmade);
            $commentmade->setCommenterId($this);
        }

        return $this;
    }

    public function removeCommentmade(Comment $commentmade): static
    {
        if ($this->commentmade->removeElement($commentmade)) {
            // set the owning side to null (unless already changed)
            if ($commentmade->getCommenterId() === $this) {
                $commentmade->setCommenterId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentgiven(): Collection
    {
        return $this->commentgiven;
    }

    public function addCommentgiven(Comment $commentgiven): static
    {
        if (!$this->commentgiven->contains($commentgiven)) {
            $this->commentgiven->add($commentgiven);
            $commentgiven->setCommentedId($this);
        }

        return $this;
    }

    public function removeCommentgiven(Comment $commentgiven): static
    {
        if ($this->commentgiven->removeElement($commentgiven)) {
            // set the owning side to null (unless already changed)
            if ($commentgiven->getCommentedId() === $this) {
                $commentgiven->setCommentedId(null);
            }
        }

        return $this;
    }
}
