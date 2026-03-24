<?php

namespace App\Repository;

use App\Entity\Hike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hike>
 */
class HikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hike::class);
    }


    //Creation d'une jointure pour récupérer toutes les infos d'une rando et de ses objets associés
    //A compléter au besoin
    public function hikeFullInfo()
    {
        $qb = $this->createQueryBuilder('hike');
        $qb
            ->join('hike.difficulty', 'difficulty') //jointure à la table difficulty
            ->addSelect('difficulty') // Les colonnes à rechercher
            ->join('hike.campus', 'campus')
            ->addSelect('campus')
            ->join('hike.location', 'location')
            ->addSelect('location')
            ->join('hike.planner', 'planner')
            ->addSelect('planner')
            ->join('hike.status', 'status')
            ->addSelect('status')
            ->addOrderBy('hike.dateEvent', 'ASC');

        $query = $qb->getQuery(); //génère la requête

        return $query->getResult(); //renvoie la requête
    }
}

