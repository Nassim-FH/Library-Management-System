<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Book title is required')]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Book title must be at least {{ limit }} characters long',
        maxMessage: 'Book title cannot be longer than {{ limit }} characters'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 13)]
    #[Assert\NotBlank(message: 'ISBN is required')]
    #[Assert\Regex(
        pattern: '/^[\d-]+$/',
        message: 'ISBN should only contain digits and hyphens'
    )]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    #[ORM\JoinTable(name: 'book_author')]
    #[Assert\Count(
        min: 1,
        minMessage: 'You must select at least one author'
    )]
    private Collection $authors;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: BorrowingHistory::class)]
    private Collection $borrowingHistories;

    #[ORM\Column]
    private ?int $availableCopies = 1;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->borrowingHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection<int, BorrowingHistory>
     */
    public function getBorrowingHistories(): Collection
    {
        return $this->borrowingHistories;
    }

    public function addBorrowingHistory(BorrowingHistory $borrowingHistory): static
    {
        if (!$this->borrowingHistories->contains($borrowingHistory)) {
            $this->borrowingHistories->add($borrowingHistory);
            $borrowingHistory->setBook($this);
        }

        return $this;
    }

    public function removeBorrowingHistory(BorrowingHistory $borrowingHistory): static
    {
        if ($this->borrowingHistories->removeElement($borrowingHistory)) {
            // set the owning side to null (unless already changed)
            if ($borrowingHistory->getBook() === $this) {
                $borrowingHistory->setBook(null);
            }
        }

        return $this;
    }

    public function getAvailableCopies(): ?int
    {
        return $this->availableCopies;
    }

    public function setAvailableCopies(int $availableCopies): static
    {
        $this->availableCopies = $availableCopies;

        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->availableCopies > 0;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
