<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Hike;
use App\Form\Model\HikeFilterDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

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
    public function HikeFullInfo(int $id)
    {
        $qb = $this->createQueryBuilder('hike');
        $qb
            ->join('hike.difficulty', 'difficulty') //jointure à la table difficulty
            ->addSelect('difficulty') // Les colonnes à rechercher
            ->join('hike.campus', 'campus')
            ->addSelect('campus')
            ->join('hike.location', 'location')
            ->addSelect('location')
            ->join('hike.participants', 'participants')
            ->addSelect('participants')
            ->join('hike.planner', 'planner')
            ->addSelect('planner')
            ->join('hike.status', 'status')
            ->addSelect('status')
            ->addOrderBy('hike.dateEvent', 'ASC')
            ->where('hike.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery(); //génère la requête

        return $query->getOneOrNullResult(); //renvoie la requête
    }


    //récupération de toutes les randos et de leurs infos associées
    public function AllHikesFullInfo()
    {
        $qb = $this->createQueryBuilder('hike');
        $qb
            ->join('hike.difficulty', 'difficulty') //jointure à la table difficulty
            ->addSelect('difficulty') // Les colonnes à rechercher
            ->join('hike.campus', 'campus')
            ->addSelect('campus')
            ->join('hike.location', 'location')
            ->addSelect('location')
            ->join('hike.participants', 'participants')
            ->addSelect('participants')
            ->join('hike.planner', 'planner')
            ->addSelect('planner')
            ->join('hike.status', 'status')
            ->addSelect('status')
            ->addOrderBy('hike.dateEvent', 'ASC');

        $query = $qb->getQuery(); //génère la requête

        return $query->getResult(); //renvoie la requête
    }

    public function hikeFiltered(HikeFilterDTO $hikeFilterDTO)
    {
        $qb = $this->createQueryBuilder('hike'); //récupère toutes les hikes

        $creee = 'Créée';
        $annulee = 'Annulée';
        $archivee = 'Archivée';

        $qb
            ->join('hike.status', 'status')
            ->addSelect('status')
            ->where('status.label NOT IN (:exclues)')
            ->setParameter('exclues', [$creee, $annulee, $archivee]);


        //Si une condition du form est remplie, on ajoute un paramètre à la requête pour filtrer
        if ($hikeFilterDTO->getName()) {
            $qb->where('hike.name LIKE :nameSelected')
                ->setParameter('nameSelected', '%' . $hikeFilterDTO->getName() . '%');
        }

        if ($hikeFilterDTO->getCampus()) {
            $qb->join('hike.campus', 'campus')
                ->addSelect('campus')
                ->andWhere('hike.campus = :campusSelected')
                ->setParameter('campusSelected', $hikeFilterDTO->getCampus());
        }
        if ($hikeFilterDTO->getDateStart()) {
            $qb->andWhere('hike.dateEvent >= :dateStart')
                ->setParameter('dateStart', $hikeFilterDTO->getDateStart());
        }
        if ($hikeFilterDTO->getDateEnd()) {
            $qb->andWhere('hike.dateEvent <= :dateEnd')
                ->setParameter('dateEnd', $hikeFilterDTO->getDateEnd());
        }
        if ($hikeFilterDTO->isOrganise()) {
            $qb->andWhere('hike.planner = :user')
                ->setParameter('user', $hikeFilterDTO->getUser());
        }
        if ($hikeFilterDTO->isParticipe()) {
            $qb
                ->join('hike.participants', 'p')
                ->andWhere('p = :user')
                ->setParameter('user', $hikeFilterDTO->getUser());
        }
        if ($hikeFilterDTO->isParticipePas()) {
            $qb
                ->andWhere(':user NOT MEMBER OF hike.participants')
                ->setParameter('user', $hikeFilterDTO->getUser());
        }
        if ($hikeFilterDTO->isTerminee()) {

            $date = new \DateTime();
            date_format($date, 'd-m-Y');

            $qb->andWhere('hike.dateEvent <= :today')
                ->setParameter('today', $date);
        }

        $query = $qb->getQuery(); //génère la requête

        return $query->getResult(); //renvoie la requête
    }

    public function findAllHikesPublished()
    {

        $creee = 'Créée';
        $annulee = 'Annulée';
        $archivee = 'Archivée';

        $qb = $this->createQueryBuilder('hike');
        $qb
            ->join('hike.status', 'status')
            ->addSelect('status')
            ->where('status.label NOT IN (:exclues)')
            ->setParameter('exclues', [$creee, $annulee, $archivee]);

        $query = $qb->getQuery();

        return $query->getResult();

    }
}

