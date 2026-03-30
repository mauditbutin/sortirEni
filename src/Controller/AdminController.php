<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UploadCSVType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\HikeRepository;
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
}
