<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Search books by title, author name, or category
     */
    public function searchBooks(?string $search, ?int $categoryId, ?int $authorId)
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.category', 'c')
            ->addSelect('a', 'c');

        if ($search) {
            $qb->andWhere('b.title LIKE :search OR a.name LIKE :search OR b.description LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($categoryId) {
            $qb->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        if ($authorId) {
            $qb->andWhere('a.id = :authorId')
                ->setParameter('authorId', $authorId);
        }

        return $qb->orderBy('b.title', 'ASC')
            ->getQuery();
    }

    /**
     * Find available books (with available copies > 0)
     */
    public function findAvailableBooks()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.category', 'c')
            ->addSelect('a', 'c')
            ->where('b.availableCopies > 0')
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find books by category
     */
    public function findByCategory(int $categoryId)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.category', 'c')
            ->addSelect('a', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
