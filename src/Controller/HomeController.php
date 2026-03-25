<?php

namespace App\Controller;

use App\Entity\Hike;
use App\Form\HikeFilterType;
use App\Form\Model\HikeFilterDTO;
use App\Repository\HikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/home', name: 'home')]
final class HomeController extends AbstractController
{
    #[Route('', name: '')]
    public function home(HikeRepository $hikeRepository, Request $request): Response
    {

        $hikeDTO = new HikeFilterDTO();
        $hikes = $hikeRepository->hikeFullInfo();
        $form = $this->createForm(HikeFilterType::class, $hikeDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hikes = $hikeRepository->hikeFiltered($hikeDTO);
        }

        return $this->render('home/home.html.twig', ['hikes' => $hikes, 'form' => $form]);
    }
}
