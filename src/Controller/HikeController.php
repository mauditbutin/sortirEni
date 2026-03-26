<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Hike;
use App\Entity\Status;
use App\Form\CancelHikeType;
use App\Form\HikeCreateType;
use App\Form\LocationCreateType;
use App\Repository\CityRepository;
use App\Repository\HikeRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Security\Voter\HikeVoter;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use phpDocumentor\Reflection\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hike', name: 'hike_')]
final class HikeController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function hikeCreate(
        EntityManagerInterface $manager,
        CityRepository         $cityRepository,
        Request                $request): Response
    {

        $hike = new Hike();
        //Fonction d'authorisation d'accès du HikeVoter
        $this->denyAccessUnlessGranted(HikeVoter::VIEW, $hike);
        $hike->setCampus($this->getUser()->getCampus());
        $hikeForm = $this->createForm(HikeCreateType::class, $hike);


        $hikeForm->handleRequest($request);

        if ($hikeForm->isSubmitted() && $hikeForm->isValid()) {

            // Gestion de l'image
            $file = $hikeForm->get('picture')->getData();
            if ($file) {
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
            if ($hikeForm->getClickedButton() && 'create' === $hikeForm->getClickedButton()->getName()) {
                $hike->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Créée']));
                $this->addFlash('success', 'Votre randonnée a bien été créée, n\'hésitez pas à la publier');
            } else if ($hikeForm->getClickedButton() && 'publish' === $hikeForm->getClickedButton()->getName()) {
                $hike->setStatus($manager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']));
                $this->addFlash('success', 'Votre randonnée a bien été publiée');
            }

            $manager->persist($hike);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        // Formulaire d'ajout de lieux
        $location = new \App\Entity\Location();
        $locationForm = $this->createForm(LocationCreateType::class, $location);

        return $this->render('hike/create.html.twig', ['hikeForm' => $hikeForm, 'locationForm' => $locationForm]);
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '[0-9]+'])]
    public function detail(int $id, HikeRepository $hikeRepository): Response
    {
        $user = $this->getUser();
        $hike = $hikeRepository->HikeFullInfo($id);

        if (!$hike) {
            throw $this->createNotFoundException('Randonnée introuvable.');
        }

        // Fonction d'authorisation d'accès du HikeVoter
        $this->denyAccessUnlessGranted(HikeVoter::VIEW, $hike);

        return $this->render('hike/detail.html.twig', [
            'hike' => $hike,
            'user' => $user
        ]);
    }

    #[Route('/{id}/subscribe', name: 'subscribe', requirements: ['id' => '[0-9]+'])]
    public function subscribe(HikeRepository $hikeRepository, int $id, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $userConnected = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['username' => $userConnected]);
        $hike = $hikeRepository->find($id);

        $this->denyAccessUnlessGranted(HikeVoter::SUBSCRIBE, $hike);

        $hike->addParticipant($user);

        $entityManager->persist($hike);
        $entityManager->flush();

        return $this->redirectToRoute('hike_detail', ['id' => $hike->getId()]);

    }

    #[Route('/{id}/unsubscribe', name: 'unsubscribe', requirements: ['id' => '[0-9]+'])]
    public function unsubscribe(HikeRepository $hikeRepository, int $id, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $userConnected = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['username' => $userConnected]);
        $hike = $hikeRepository->find($id);


        $this->denyAccessUnlessGranted(HikeVoter::UNSUBSCRIBE, $hike);

        $hike->removeParticipant($user);

        $entityManager->persist($hike);
        $entityManager->flush();

        return $this->redirectToRoute('hike_detail', ['id' => $hike->getId()]);

    }


    #[Route('/{id}/cancel', name: 'cancel', requirements: ['id' => '[0-9]+'])]
    public function cancel(HikeRepository $hikeRepository, int $id, EntityManagerInterface $entityManager, StatusRepository $statusRepository, Request $request): Response
    {

        $hike = $hikeRepository->find($id);
        $status = $statusRepository->findOneBy(['label' => 'Annulée']);
        $form = $this->createForm(CancelHikeType::class);
        $this->denyAccessUnlessGranted(HikeVoter::CANCEL, $hike);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $description = $form->get('description')->getData();
            $hike->setDescription($description);

            $hike->setStatus($status);

            $entityManager->persist($hike);
            $entityManager->flush();
        }


        return $this->redirectToRoute('hike_detail', ['id' => $hike->getId(), 'form' => $form]);

    }

}
