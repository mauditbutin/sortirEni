<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function getLocationsByCity(int $id)
    {
        $qb = $this->createQueryBuilder('location');
        $qb
            ->leftjoin('location.city', 'city')
            ->addSelect('city')
            ->where('city.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function getLocationInfosById(int $id)
    {
        $qb = $this->createQueryBuilder('location')
            ->leftjoin('location.city', 'city')
            ->addSelect('city')
            ->where('location.id = :id')
            ->setParameter('id', $id);
        $query = $qb->getQuery();
        return $query->getResult();
    }

}
