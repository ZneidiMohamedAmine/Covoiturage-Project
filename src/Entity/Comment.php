<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $StarsNumber = null;

    #[ORM\ManyToOne(inversedBy: 'commentmade')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $commenterId = null;

    #[ORM\ManyToOne(inversedBy: 'commentgiven')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $commentedId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStarsNumber(): ?int
    {
        return $this->StarsNumber;
    }

    public function setStarsNumber(?int $StarsNumber): static
    {
        $this->StarsNumber = $StarsNumber;

        return $this;
    }

    public function getCommenterId(): ?User
    {
        return $this->commenterId;
    }

    public function setCommenterId(?User $commenterId): static
    {
        $this->commenterId = $commenterId;

        return $this;
    }

    public function getCommentedId(): ?User
    {
        return $this->commentedId;
    }

    public function setCommentedId(?User $commentedId): static
    {
        $this->commentedId = $commentedId;

        return $this;
    }
}
