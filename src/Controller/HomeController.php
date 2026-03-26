<?php

namespace App\Controller;


use App\Form\HikeFilterType;
use App\Form\Model\HikeFilterDTO;
use App\Repository\HikeRepository;
use App\Repository\StatusRepository;
use App\Security\Voter\HikeVoter;
use App\Service\UpdateStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/home', name: 'home')]
final class HomeController extends AbstractController
{
    #[Route('', name: '')]
    public function home(HikeRepository $hikeRepository, Request $request, UpdateStatus $updateStatus): Response
    {

        //Mise à jour des statuts
        $updateStatus->updateStatus();

        $hikeDTO = new HikeFilterDTO();
        $hikeDTO->setUser($this->getUser()); //récupération de l'utilisateur connecté et set dans le DTO

        $hikes = $hikeRepository->hikeFiltered($hikeDTO);
        //Fonction d'authorisation d'accès du HikeVoter


        $form = $this->createForm(HikeFilterType::class, $hikeDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hikes = $hikeRepository->hikeFiltered($hikeDTO);
        }

        return $this->render('home/home.html.twig', ['hikes' => $hikes, 'form' => $form]);
    }
}
