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
        $hike->setCampus($this->getUser()->getCampus());
        $hikeForm = $this->createForm(HikeCreateType::class, $hike);

        $hikeForm->handleRequest($request);

        if ($hikeForm->isSubmitted() && $hikeForm->isValid()){

            // Gestion de l'image
            $file = $hikeForm->get('picture')->getData();
            if($file){
                $newFileName = str_replace(' ', '-', $hike->getName()) . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move('images/hikes', $newFileName);
                $hike->setPicture($newFileName);
            } else {
                $hike->setPicture('image-not-found.webp');
            }

            // Gestion des autres champs auto
            $hike->setPlanner($this->getUser());
            $hike->addParticipant($this->getUser());

            // Ajout du statut selon le bouton cliqué
            if($hikeForm->getClickedButton() && 'create' === $hikeForm->getClickedButton()->getName()){
                $hike->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
                $this->addFlash('success', 'Votre randonnée a bien été créée, n\'hésitez pas à la publier');
            } else if ($hikeForm->getClickedButton() && 'publish' === $hikeForm->getClickedButton()->getName()){
                $hike->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
                $this->addFlash('success', 'Votre randonnée a bien été publiée');
            }

            $manager->persist($hike);
            $manager->flush();

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
