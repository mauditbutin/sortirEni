<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Difficulty;
use App\Entity\Hike;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/home', name: 'home')]
final class HomeController extends AbstractController
{
    #[Route('', name: '')]
    public function home(): Response
    {
        //Juste pour les tests d'affichage --- a supprimer une fois les fixtures faites
        $difficulty = new Difficulty();
        $difficulty->setLabel('Extreme');

        $status = new Status();
        $status->setLabel('publié');

        $city = new City();
        $city->setName("Rennes test");
        $city->setZipcode('zipcodetest');

        $location = new Location();
        $location->setName("une position test");
        $location->setAddress('une adresse test, rue des fraises');
        $location->setCity($city);

        $campus = new Campus();
        $campus->setName('CDB');

        $user = new User();
        $user->setCampus($campus);
        $user->setPicture('pasdephoto');
        $user->setActive(true);
        $user->setEmail('coucou@coucou.com');
        $user->setFirstname('Bob');
        $user->setLastname('Lefou');
        $user->setUsername('testeurfou');
        $user->setPassword('123456');
        $user->setPhoneNumber('0612345678');


        $sortie = new Hike();
        $sortie->setName('sortie test');
        $sortie->setDateEvent(\DateTime::createFromFormat('d-m-Y','25-04-2026'));
        $sortie->setDuration(125);
        $sortie->setNbMaxSubscription(10);
        $sortie->setDescription('une sortie test pour voir si ça marche');
        $sortie->setPicture('pas de photo');
        $sortie->setStatus($status);
        $sortie->setDifficulty($difficulty);
        $sortie->setLocation($location);
        $sortie->setCampus($campus);
        $sortie->setPlanner($user);

        // Supprimer au dessus

        return $this->render('home/home.html.twig', ['sortie' => $sortie]);
    }
}
