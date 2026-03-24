<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Difficulty;
use App\Entity\Hike;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\HikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/home', name: 'home')]
final class HomeController extends AbstractController
{
    #[Route('', name: '')]
    public function home(HikeRepository $hikeRepository): Response
    {
        $hikes = $hikeRepository->hikeFullInfo();

        return $this->render('home/home.html.twig', ['hikes' => $hikes]);
    }
}
