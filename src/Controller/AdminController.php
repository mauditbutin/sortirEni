<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserType;
use App\Form\UploadCSVType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\HikeRepository;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

            if (($handle = fopen($file->getPathname(), "r")) !== false) {

                // Read and process the lines.
                // Skip the first line if the file includes a header
                fgetcsv($handle, 0, ';');
                while (($data = fgetcsv($handle, 0, ';')) !== false) {
                    // Do the processing: Map line to entity

                    $user = new User();
                    // Assign fields
                    $user
                        ->setUsername($data[0])
                        ->setLastname($data[1])
                        ->setFirstname($data[2])
                        ->setEmail($data[3])
                        //Password temporaire
                        ->setPassword($userPasswordHasher->hashPassword($user, '123456'))
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
