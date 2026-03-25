<?php

namespace App\Controller;

use App\Entity\Hike;
use App\Entity\Status;
use App\Form\HikeCreateType;
use App\Repository\HikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hike', name: 'hike_')]
final class HikeController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function hikeCreate(EntityManagerInterface $manager, Request $request): Response
    {
        $hike = new Hike();
        $hikeForm = $this->createForm(HikeCreateType::class, $hike);

        $hikeForm->handleRequest($request);

        if ($hikeForm->isSubmitted() && $hikeForm->isValid()) {
            // Gérer si c'est juste créé et pas soumis
            $hike->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
            $hike->setPlanner($this->getUser());
            $hike->addParticipant($this->getUser());

            $manager->persist($hike);
            $manager->flush();

            $this->addFlash('success', 'Votre randonnée a bien été publiée');
            return $this->redirectToRoute('home');
        }


        return $this->render('hike/create.html.twig', ['hikeForm' => $hikeForm]);
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '[0-9]+'])]
    public function detail(int $id, HikeRepository $hikeRepository): Response
    {
        $user = $this->getUser();
        $hike = $hikeRepository->find($id);

        return $this->render('hike/detail.html.twig', [
            'hike' => $hike,
            'user' => $user
        ]);
    }


}
