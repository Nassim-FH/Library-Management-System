<?php

namespace App\Entity;

use App\Repository\BorrowingHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BorrowingHistoryRepository::class)]
class BorrowingHistory
{
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_OVERDUE = 'overdue';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'borrowingHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'borrowingHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $borrowDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(
        choices: [self::STATUS_BORROWED, self::STATUS_RETURNED, self::STATUS_OVERDUE],
        message: 'Choose a valid status'
    )]
    private ?string $status = self::STATUS_BORROWED;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    public function __construct()
    {
        $this->borrowDate = new \DateTime();
        $this->status = self::STATUS_BORROWED;
        // Set due date to 14 days from now
        $this->dueDate = (new \DateTime())->modify('+14 days');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(\DateTimeInterface $borrowDate): static
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): static
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function isOverdue(): bool
    {
        if ($this->status === self::STATUS_RETURNED) {
            return false;
        }

        return $this->dueDate && $this->dueDate < new \DateTime();
    }
}
