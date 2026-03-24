<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/account/{id}', name: 'app_profile')]
    public function show(int $id, UserRepository $userRepository ): Response
    {
        $user = $userRepository->find($id);
        // if user not founded
        if (!$user) {
            throw $this->createNotFoundException('User not found !!!');
        }
        // render - make back html page
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }
}
