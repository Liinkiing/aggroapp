<?php

namespace App\Repository;

use App\Entity\VideoRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VideoRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoRequest[]    findAll()
 * @method VideoRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoRequest::class);
    }
}
