<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\BorrowingHistory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class BorrowingService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Borrow a book for a user
     *
     * @param User $user
     * @param Book $book
     * @return BorrowingHistory|null Returns the borrowing history or null if the book is not available
     */
    public function borrowBook(User $user, Book $book): ?BorrowingHistory
    {
        // Check if the book is available
        if (!$book->isAvailable()) {
            return null;
        }

        // Create borrowing history
        $borrowing = new BorrowingHistory();
        $borrowing->setUser($user);
        $borrowing->setBook($book);
        $borrowing->setBorrowDate(new \DateTime());
        $borrowing->setStatus(BorrowingHistory::STATUS_BORROWED);
        
        // Set due date to 14 days from now
        $dueDate = new \DateTime();
        $dueDate->modify('+14 days');
        $borrowing->setDueDate($dueDate);

        // Decrease available copies
        $book->setAvailableCopies($book->getAvailableCopies() - 1);

        // Persist changes
        $this->entityManager->persist($borrowing);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $borrowing;
    }

    /**
     * Return a borrowed book
     *
     * @param BorrowingHistory $borrowing
     * @return bool Returns true if the book was successfully returned
     */
    public function returnBook(BorrowingHistory $borrowing): bool
    {
        // Check if the book is already returned
        if ($borrowing->getStatus() === BorrowingHistory::STATUS_RETURNED) {
            return false;
        }

        // Update borrowing history
        $borrowing->setReturnDate(new \DateTime());
        $borrowing->setStatus(BorrowingHistory::STATUS_RETURNED);

        // Increase available copies
        $book = $borrowing->getBook();
        $book->setAvailableCopies($book->getAvailableCopies() + 1);

        // Persist changes
        $this->entityManager->persist($borrowing);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Check and update overdue borrowings
     */
    public function updateOverdueBorrowings(): void
    {
        $repository = $this->entityManager->getRepository(BorrowingHistory::class);
        $repository->updateOverdueStatus();
    }

    /**
     * Check if a user has an active borrowing for a specific book
     */
    public function hasActiveBorrowing(User $user, Book $book): bool
    {
        $borrowing = $this->entityManager->getRepository(BorrowingHistory::class)
            ->findOneBy([
                'user' => $user,
                'book' => $book,
                'status' => [BorrowingHistory::STATUS_BORROWED, BorrowingHistory::STATUS_OVERDUE]
            ]);

        return $borrowing !== null;
    }
}
