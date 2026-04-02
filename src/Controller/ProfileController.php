<?php

namespace App\Controller;

use App\Form\ProfileEditType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
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
    public function show(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);
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
    ): Response {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }

        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $form = $this->createForm(ProfileEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                if ($user->getPicture()) {
                    $fileUploader->deleteFile($user->getPicture(), 'images/profilePictures');
                }

                $user->setPicture(
                    $fileUploader->uploadFile(
                        $pictureFile,
                        $this->getParameter('kernel.project_dir') . '/public/images/profilePictures',
                        $user->getUsername()
                    )
                );
            }

            $plainPassword = $form->get('plainPassword')->getData();

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le profil a bien été modifié');

            return $this->redirectToRoute('app_profile', ['id' => $user->getId()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
