<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\HikeRepository;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
final class AdminController extends AbstractController
{
    #[Route('/main', name: 'main')]
    public function index(HikeRepository $hikeRepository, UserRepository $userRepository, CampusRepository $campusRepository, CityRepository $cityRepository): Response
    {

        //Récupération des données
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);
        $hikes = $hikeRepository->AllHikesFullInfo();
        $users = $userRepository->AllUsersAndInfos();
        $campus = $campusRepository->allCampusAndInfos();
        $villes = $cityRepository->allCityAndInfos();


        return $this->render('admin/admin.html.twig', ['hikes' => $hikes, 'users' => $users, 'villes' => $villes, 'campus' => $campus]);
    }
}
