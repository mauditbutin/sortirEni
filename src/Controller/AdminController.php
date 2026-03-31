<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\User;
use App\Form\AddCampusType;
use App\Form\AddCityType;
use App\Form\AddUserType;
use App\Form\UploadCSVType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\HikeRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Clock\now;

#[Route('/admin', name: 'admin_')]
final class AdminController extends AbstractController
{
    #[Route('/main/{activeTab}', name: 'main', defaults: ['activeTab' => 'tabUser'])]
    public function index(HikeRepository   $hikeRepository,
                          UserRepository   $userRepository,
                          CampusRepository $campusRepository,
                          CityRepository   $cityRepository,
                          string           $activeTab
    ): Response
    {

        //Récupération des données
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);
        $hikes = $hikeRepository->AllHikesFullInfo();
        $users = $userRepository->AllUsersAndInfos();
        $campus = $campusRepository->allCampusAndInfos();
        $villes = $cityRepository->allCityAndInfos();

        return $this->render('admin/admin.html.twig', ['hikes' => $hikes, 'users' => $users, 'villes' => $villes, 'campus' => $campus, 'activeTab' => $activeTab]);
    }

    // ====================== Deacivation of User ========================
    #[Route('/user/{id}/deactivate', name: 'deactivate_user')]
    public function deactivateUser(
        int                    $id,
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $this->getUser()); // Dont sure if this is necessary

        $user = $userRepository->find($id); // searching by id

        if (!$user) {
            throw $this->createNotFoundException('User not found, sorry');
        }

        if ($user === $this->getUser()) {
            $this->addFlash('danger', 'Its not good. This is your account buddy !');
            return $this->redirectToRoute('admin_main');
        }

        // ==== Make user deactive ====
        $user->setActive(false);

        // unsubscribing the user from all their participation in hikes
        foreach ($user->getParticipatedHikes()->toArray() as $hike) {
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
        int                    $id,
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $this->getUser());

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found, sorry');
        }

        $user->setActive(true);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User' . $user->getFirstname() . ' ' . $user->getLastname() . ' is now activated ! ');

        return $this->redirectToRoute('admin_main');

    }


    // ----------------- Ajouter un utilisateur via formulaire ------------------
    #[Route('/formUser', name: 'formUser')]
    public function formUser(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        FileUploader                $fileUploader): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);

        $userCreated = new User();
        $form = $this->createForm(AddUserType::class, $userCreated);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //Gestion du mot de passe
            /** @var string $plainPassword */
            $plainPassword = $form->get('password')->getData();

            // encode the plain password
            $userCreated->setPassword($userPasswordHasher->hashPassword($userCreated, $plainPassword));
            // ------

            //Gestion de l'image de profil
            /**
             * @var UploadedFile $file
             */
            $file = $form->get('picture')->getData();
            if ($file) {
                $userCreated->setPicture($fileUploader->uploadFile($file, 'images/profilePictures', $userCreated->getUserIdentifier()));
            } else {
                $userCreated->setPicture('default.jpg');
            }

            $entityManager->persist($userCreated);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur Créé');
            return $this->redirectToRoute('admin_main');

        }

        return $this->render('admin/formUser.html.twig', ['formUser' => $form]);

    }


    // ----------------- Ajouter un utilisateur via fichier.csv ------------------
    #[Route('/uploadCSV', name: 'uploadcsv')]
    public function uploadCSV(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);
        $form = $this->createForm(UploadCSVType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            /** @var UploadedFile */
            $file = $form->get('submitFile')->getData();
            // Open the file

            //récup du mdp
            $plainPassword = $form->get('password')->getData();
            //récup du campus
            $campus = $form->get('campus')->getData();

            if (($handle = fopen($file->getPathname(), "r")) !== false) {

                // Read and process the lines.
                // Skip the first line if the file includes a header
                fgetcsv($handle, 0, ';'); //Lit une première fois le fichier pour sauter le header ensuite
                while (($data = fgetcsv($handle, 0, ';')) !== false) {
                    // Do the processing: Map line to entity

                    $user = new User();
                    // Assign fields
                    $user
                        ->setUsername($data[0])
                        ->setLastname($data[1])
                        ->setFirstname($data[2])
                        ->setEmail($data[3])
                        ->setPassword($userPasswordHasher->hashPassword($user, $plainPassword))
                        ->setCampus($campus)
                        ->setPhoneNumber($data[4]);

                    $entityManager->persist($user);
                }
                fclose($handle);
                $entityManager->flush();

                $this->addFlash('success', 'Données ajoutées à la base');
                return $this->redirectToRoute('admin_main');
            }


        }
        return $this->render('admin/uploadCSV.html.twig', ['form' => $form]);
    }

    // =================== Suppression d'un utilisateur =======================
    #[Route('/user/{id}/delete', name: 'delete_user')]
    public function deleteUser(
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

        if ($user === $this->getUser())
        {
            $this->addFlash('danger', 'You cannot delete your own account, this is crazy.');
            return $this->redirectToRoute('admin_main');
        }

        $fullName = $user->getFirstname() . ' ' . $user->getLastname();

        // ====== where user like planner ============
        foreach ($user->getPlannedHikes()->toArray() as $hike)
        {
            $hike->setPlanner(null);
        }

        // ====== where user like participant (remove in future hikes, but rest in passed hikes) ============
        $now = new \DateTime();
        foreach ($user->getParticipatedHikes()->toArray() as $hike)
            {
                if ($hike->getDateEvent() > $now)
                {
                    $hike->removeParticipant($user);
                    $user->removeParticipatedHike($hike);
                }
            }
        // ======= delete photo of profil ========
        if ($user->getPicture())
        {
            $picturePath = $this->getParameter('kernel.project_dir')
                . '/public/images/profilePictures/'
                . $user->getPicture();

            if (file_exists($picturePath))
            {
                unlink($picturePath);
            }
        }
        // ======= delete in datab ==========
        $entityManager->remove($user); // which user to DELETE
        $entityManager->flush(); // SLQ DELETE

        $this->addFlash('success', 'User' . $fullName . ' was deleted');
        return $this->redirectToRoute('admin_main');

    }

    // ----------------- Annuler une randonnée -----------------------------------
    #[Route('/cancelHike/{id}', name: 'cancelHike')]
    public function cancelHike(int $id, HikeRepository $hikeRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);

        $statusAnnulee = $statusRepository->findOneBy(['label' => 'Annulée']);
        $hikeToCancel = $hikeRepository->find($id);
        $hikeToCancel->setStatus($statusAnnulee);

        $entityManager->persist($hikeToCancel);
        $entityManager->flush();

        $this->addFlash('success', 'Randonnée annulée');

        return $this->redirectToRoute('admin_main', ['activeTab' => 'tabRando']);

    }

    // ----------------- Ajouter une ville -----------------------------------
    #[Route('/addCity', name: 'addCity')]
    public function addCity(EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);
        $city = new City();
        $form = $this->createForm(AddCityType::class, $city);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', 'Ville ajoutée');
            return $this->redirectToRoute('admin_main', ['activeTab' => 'tabVille']);
        }

        return $this->render('admin/addCity.html.twig', ['form' => $form]);

    }

    #[Route('/addCampus', name: 'addCampus')]
    public function addCampus(EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::ADMIN, $user);
        $campus = new Campus();
        $form = $this->createForm(AddCampusType::class, $campus);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Campus ajouté !');
            return $this->redirectToRoute('admin_main', ['activeTab' => 'tabCampus']);
        }

        return $this->render('admin/addCampus.html.twig', ['form' => $form]);

    }

}
