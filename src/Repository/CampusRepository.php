<?php

namespace App\Repository;

use App\Entity\Campus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campus>
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

    public function allCampusAndInfos()
    {

        $qb = $this->createQueryBuilder('campus');
        $qb
            ->leftJoin('campus.hikes', 'hikes')
            ->addSelect('hikes')
            ->leftJoin('campus.users', 'users')
            ->addSelect('users')
            ->addOrderBy('campus.name', 'ASC');

        $query = $qb->getQuery(); //génère la requête

        return $query->getResult(); //renvoie la requête
    }

}
