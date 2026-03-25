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
            $dateEvent = $hike->getDateEvent();
            $dateSubscription = $hike->getDateSubscription();

            if (($dateEvent > $date) && ($dateSubscription > $date)) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Ouverte']));
            } elseif (($dateEvent > $date) && ($hike->getDateSubscription() < $date)) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Clôturée']));
            }
            
            if ($dateEvent->format('Y-m-d') === $date->format('Y-m-d')) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Activité en cours']));
            } elseif (($dateEvent < $date)) {
                $hike->setStatus($statusRepository->findOneBy(['label' => 'Passée']));
            }


            $entityManager->persist($hike);
        }

        $entityManager->flush();

    }
}
