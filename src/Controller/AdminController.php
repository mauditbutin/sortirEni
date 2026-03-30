<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\HikeRepository;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
final class AdminController extends AbstractController
{
    #[Route('/main', name: 'main')]
    public function index(HikeRepository $hikeRepository, UserRepository $userRepository, CampusRepository $campusRepository, CityRepository $cityRepository): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);
        $hikes = $hikeRepository->AllHikesFullInfo();
        $users = $userRepository->AllUsersAndInfos();
        $campus = $campusRepository->allCampusAndInfos();
        $villes = $cityRepository->allCityAndInfos();

        return $this->render('admin/admin.html.twig', ['hikes' => $hikes, 'users' => $users, 'villes' => $villes, 'campus' => $campus]);
    }

    // ====================== Deacivation of User ========================
    #[Route('/user/{id}/deactivate', name: 'deactivate_user')]
    public function deactivateUser(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $this->getUser()); // Dont sure if this is necessary

        $user = $userRepository->find($id); // searching by id

        if (!$user)
        {
            throw $this->createNotFoundException('User not found, sorry');
        }

        if ($user === $this->getUser())
        {
            $this->addFlash('danger', 'Its not good. This is your account buddy !');
            return $this->redirectToRoute('admin_main');
        }

        // ==== Make user deactive ====
        $user->setActive(false);

        // unsubscribing the user from all their participation in hikes
        foreach ($user->getParticipatedHikes()->toArray() as $hike)
        {
            $hike->removeParticipant($user);
            $user->removeParticipatedHike($hike);
        }

        // === BDD ===
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User ' . $user->getFirstname() . ' ' . $user->getLastname() . ' is now deactivated ! ');
        return $this->redirectToRoute('admin_main');

    }

    // ====================== Activation of User ========================
    #[Route('/user/{id}/activate', name: 'activate_user')]
    public function activateUser(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $this->getUser());

        $user = $userRepository->find($id);

        if (!$user)
        {
            throw $this->createNotFoundException('User not found, sorry');
        }

        $user->setActive(true);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User' . $user->getFirstname() . ' ' . $user->getLastname() . ' is now activated ! ');

        return $this->redirectToRoute('admin_main');

    }
}
