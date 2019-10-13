<?php

namespace App\Repository;

use App\Entity\ApiUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ApiUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiUser[]    findAll()
 * @method ApiUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiUser::class);
    }
}
