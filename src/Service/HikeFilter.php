<?php

namespace App\Service;

use App\Form\Model\HikeFilterDTO;
use App\Repository\HikeRepository;

class HikeFilter
{

    public function getClassFiltered(HikeFilterDTO $hikeFilterDTO, HikeRepository $hikeRepository)
    {

        $campus = $hikeFilterDTO->getCampus();
        $dateStart = $hikeFilterDTO->getDateStart();
        $dateEnd = $hikeFilterDTO->getDateEnd();
        $organise = $hikeFilterDTO->isOrganise();
        $participe = $hikeFilterDTO->isParticipe();
        $participePas = $hikeFilterDTO->isParticipePas();
        $terminee = $hikeFilterDTO->isTerminee();


    }
}
