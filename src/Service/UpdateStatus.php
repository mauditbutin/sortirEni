<?php

namespace App\Service;

use App\Repository\HikeRepository;
use App\Repository\StatusRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class UpdateStatus
{
    public function updateStatus(HikeRepository $hikeRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager)
    {
        $date = new DateTime('now');


        $hikes = $hikeRepository->findAllHikesPublished();

        //Uniquement pour les hikes ouvertes
        foreach ($hikes as $hike) {
            if (($hike->getDateEvent() < $date) and ($hike->getDateSubscription() < $date)) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Ouverte']));
            }
            if (($hike->getDateEvent() < $date) and ($hike->getDateSubscription() > $date)) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Clôturée']));
            }
            if (($hike->getDateEvent() == $date) and ($hike->getDateSubscription() == $date)) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Activité en cours']));
            }
            if (($hike->getDateEvent() < $date) and ($hike->getDateSubscription() > $date)) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Passée']));
            }


            $entityManager->persist($hike);
        }

        $entityManager->flush();

    }
}
