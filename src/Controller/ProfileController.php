<?php

namespace App\Controller;

use App\Form\ProfileEditType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    // ------------------------------Display the profile of user -----------------------------------------
    #[Route(path: '/account/{id}', name: 'app_profile')]
    public function show(int $id, UserRepository $userRepository ): Response
    {
        $user = $userRepository->find($id);
        // if user not founded
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }
        // render - make back html page
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    // ---------------------------Modify the profile-------------------------------------------------------
    #[Route(path: '/account/{id}/edit', name: 'app_profile_edit')]
    public function edit(
        int $id,
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader,
    ): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }
        if ($this->getUser() !== $user) {
            $this->addFlash('error','Vous n\'êtes pas autorisé à modifier ce profil');

            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(ProfileEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
//                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
//
//                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures';
//
//                $pictureFile->move($uploadDir, $newFilename);
//
//                if ($user->getPicture()) {
//                    $oldFilePath = $uploadDir . '/' . $user->getPicture();
//                    if (file_exists($oldFilePath)) {
//                        unlink($oldFilePath); // unlink() - supprime le fichier du serveur
//                    }
//                }
//
//                $user->setPicture($newFilename);
                if ($user->getPicture()){
                    $fileUploader->deleteFile($user->getPicture(), 'images/profilePictures');
                    $user->setPicture($fileUploader->uploadFile($pictureFile, 'images/profilePictures', $user->getUsername()));
                } else {
                    $user->setPicture($fileUploader->uploadFile($pictureFile, 'images/profilePictures', $user->getUsername()));
                }

            }




            $plainPassword = $form->get('plainPassword')->getData();

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le profil a bien été modifié');
            return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
