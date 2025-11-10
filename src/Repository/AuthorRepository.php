<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * Find authors ordered by name
     */
    public function findAllOrdered()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
