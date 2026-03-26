<?php

namespace App\Service;

use App\Repository\HikeRepository;
use App\Repository\StatusRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class UpdateStatus
{
    private HikeRepository $hikeRepository;
    private StatusRepository $statusRepository;
    private EntityManagerInterface $entityManager;

    //Constructeur pour ne pas avoir à injecter tout dans la fonction

    public function __construct(HikeRepository $hikeRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager)
    {

        $this->hikeRepository = $hikeRepository;
        $this->statusRepository = $statusRepository;
        $this->entityManager = $entityManager;
    }

    public function updateStatus()
    {
        $date = new DateTime('now');


        $hikes = $this->hikeRepository->findAllHikesPublished();

        //find all des états
        $status = $this->statusRepository->findAll();

        //tableau associatif des états
        $tableauDeStatus = [];
        foreach ($status as $element) {
            $tableauDeStatus [$element->getLabel()] = $element;
        }


        //Uniquement pour les hikes ouvertes
        foreach ($hikes as $hike) {
            $dateEvent = $hike->getDateEvent();
            $dateSubscription = $hike->getDateSubscription();

            if (($dateEvent > $date) && ($dateSubscription > $date)) {
                //$hike->setStatus($this->statusRepository->findOneBy(['label' => 'Ouverte']));
                $hike->setStatus($tableauDeStatus['Ouverte']);
            } elseif (($dateEvent > $date) && ($hike->getDateSubscription() < $date)) {
                // $hike->setStatus($this->statusRepository->findOneBy(['label' => 'Clôturée']));
                $hike->setStatus($tableauDeStatus['Clôturée']);
            }

            if ($dateEvent->format('Y-m-d') === $date->format('Y-m-d')) {
//                $hike->setStatus($this->statusRepository->findOneBy(['label' => 'Activité en cours']));
                $hike->setStatus($tableauDeStatus['Activité en cours']);
            } elseif (($dateEvent < $date)) {
//                $hike->setStatus($this->statusRepository->findOneBy(['label' => 'Passée']));
                $hike->setStatus($tableauDeStatus['Passée']);
            }
            if ($dateEvent->diff($date)->format('%a') > 31) {
//                $hike->setStatus($this->statusRepository->findOneBy(['label' => 'Archivée']));
                $hike->setStatus($tableauDeStatus['Archivée']);
            }


            $this->entityManager->persist($hike);
        }

        $this->entityManager->flush();

    }
}
