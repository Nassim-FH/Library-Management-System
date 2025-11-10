<?php

namespace App\Repository;

use App\Entity\BorrowingHistory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BorrowingHistory>
 */
class BorrowingHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BorrowingHistory::class);
    }

    /**
     * Find borrowing history for a specific user
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('bh')
            ->leftJoin('bh.book', 'b')
            ->addSelect('b')
            ->where('bh.user = :user')
            ->setParameter('user', $user)
            ->orderBy('bh.borrowDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find active borrowings for a user (not returned)
     */
    public function findActiveBorrowings(User $user)
    {
        return $this->createQueryBuilder('bh')
            ->leftJoin('bh.book', 'b')
            ->addSelect('b')
            ->where('bh.user = :user')
            ->andWhere('bh.status IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('statuses', [BorrowingHistory::STATUS_BORROWED, BorrowingHistory::STATUS_OVERDUE])
            ->orderBy('bh.borrowDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all active borrowings (admin view)
     */
    public function findAllActiveBorrowings()
    {
        return $this->createQueryBuilder('bh')
            ->leftJoin('bh.book', 'b')
            ->leftJoin('bh.user', 'u')
            ->addSelect('b', 'u')
            ->where('bh.status IN (:statuses)')
            ->setParameter('statuses', [BorrowingHistory::STATUS_BORROWED, BorrowingHistory::STATUS_OVERDUE])
            ->orderBy('bh.borrowDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Update overdue borrowings
     */
    public function updateOverdueStatus(): void
    {
        $qb = $this->createQueryBuilder('bh');
        $qb->update()
            ->set('bh.status', ':overdueStatus')
            ->where('bh.status = :borrowedStatus')
            ->andWhere('bh.dueDate < :now')
            ->setParameter('overdueStatus', BorrowingHistory::STATUS_OVERDUE)
            ->setParameter('borrowedStatus', BorrowingHistory::STATUS_BORROWED)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->execute();
    }
}
