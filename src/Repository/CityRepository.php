<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function allCityAndInfos()
    {

        $qb = $this->createQueryBuilder('city');
        $qb
            ->leftJoin('city.locations', 'locations')
            ->addSelect('locations')
            ->addOrderBy('city.name', 'ASC');

        $query = $qb->getQuery(); //génère la requête

        return $query->getResult(); //renvoie la requête
    }
}
