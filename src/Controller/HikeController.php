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
use App\Service\FileUploader;
use App\Service\TimeConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/hike', name: 'hike_')]
final class HikeController extends AbstractController
{

    #[Route('/create', name: 'create')]
    public function hikeCreate(
        EntityManagerInterface $manager,
        CityRepository         $cityRepository,
        FileUploader $fileUploader,
        Request                $request,
        TimeConverter $timeConverter
    ): Response
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
                $hike->setPicture($fileUploader->uploadFile($file, 'images/hikes', $hike->getName()));
            } else {
                $hike->setPicture('image-not-found.webp');
            }

            // Gestion des autres champs auto
            $hike->setPlanner($this->getUser());
            $hike->addParticipant($this->getUser());

            // Gestion du temps de la randonnée
            $hike->setDuration(
                $timeConverter->durationIntoMinutes(
                    $hikeForm->get('durationHours')->getData(), $hikeForm->get('durationMinutes')->getData()
                ));

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
    public function detail(int $id, HikeRepository $hikeRepository, TimeConverter $timeConverter): Response
    {
        $user = $this->getUser();
        $hike = $hikeRepository->HikeFullInfo($id);
        $formCancel = $this->createForm(CancelHikeType::class);

        if (!$hike) {
            throw $this->createNotFoundException('Randonnée introuvable.');
        }

        // Convertisseur du int en H/m
        $durationString = $timeConverter->minutesIntoDuration($hike->getDuration());

        // Fonction d'authorisation d'accès du HikeVoter
        $this->denyAccessUnlessGranted(HikeVoter::VIEW, $hike);

        return $this->render('hike/detail.html.twig', [
            'hike' => $hike,
            'user' => $user,
            'formCancel' => $formCancel,
            'durationString' => $durationString
        ]);
    }

    #[Route('/{id}/subscribe', name: 'subscribe', requirements: ['id' => '[0-9]+'])]
    #[IsGranted('HIKE_SUBSCRIBE', 'hike')]
    public function subscribe(Hike $hike, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $nbParticipants = count($hike->getParticipant());

        if ($nbParticipants < $hike->getNbMaxSubscription()) {
            $hike->addParticipant($user);
            $entityManager->persist($hike);
            $entityManager->flush();
            $this->addFlash('success', 'Inscription validée');
        } else {
            $this->addFlash('error', 'Il n\'y a plus de place pour cette randonnée');
        }

        return $this->redirectToRoute('hike_detail', ['id' => $hike->getId()]);

    }

    #[Route('/{id}/unsubscribe', name: 'unsubscribe', requirements: ['id' => '[0-9]+'])]
    #[IsGranted('HIKE_UNSUBSCRIBE', 'hike')]
    public function unsubscribe(Hike $hike, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $hike->removeParticipant($user);
        $entityManager->persist($hike);
        $entityManager->flush();

        $this->addFlash('success', 'Désinscription validée');
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
            $description = 'Annulée : ' . $form->get('description')->getData() . '.' . "\n" . $hike->getDescription();
            $hike->setDescription($description);

            $hike->setStatus($status);

            $entityManager->persist($hike);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie annulée');

        }


        return $this->redirectToRoute('hike_detail', ['id' => $hike->getId(), 'form' => $form]);

    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '[0-9]+'])]
    public function delete(HikeRepository $hikeRepository, int $id, EntityManagerInterface $entityManager, StatusRepository $statusRepository, Request $request): Response
    {

        $hike = $hikeRepository->find($id);
        $status = $statusRepository->findOneBy(['label' => 'Créée']);

        $this->denyAccessUnlessGranted(HikeVoter::DELETE, $hike);

        if ($hike->getStatus() == $status) {

            $entityManager->remove($hike);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie supprimée');
        }


        return $this->redirectToRoute('home');

    }

}
