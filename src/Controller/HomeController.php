<?php

namespace App\Controller;


use App\Form\HikeFilterType;
use App\Form\Model\HikeFilterDTO;
use App\Repository\HikeRepository;
use App\Repository\StatusRepository;
use App\Security\Voter\HikeVoter;
use App\Security\Voter\UserVoter;
use App\Service\UpdateStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home/{page}', name: 'home')]
    public function home(
        HikeRepository $hikeRepository,
        Request        $request,
        UpdateStatus   $updateStatus,
        int            $page = 1): Response
    {

        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);
        //Mise à jour des statuts
        $updateStatus->updateStatus();


        $hikeDTO = new HikeFilterDTO();
        $hikeDTO->setUser($this->getUser()); //récupération de l'utilisateur connecté et set dans le DTO


        //Fonction d'authorisation d'accès du HikeVoter


        $form = $this->createForm(HikeFilterType::class, $hikeDTO);
        $form->handleRequest($request);
        $hikes = $hikeRepository->hikeFiltered($hikeDTO, $page);


        // ----- Gestion des pages et du nombre de sortie par page

        $nbHikeParPages = count($hikes);
        $nbMaxPages = ceil($nbHikeParPages / 12); //CF valeur nbResultat dans HikeReposirory

        if ($page > $nbMaxPages) {
            throw $this->createNotFoundException("Plus rien ici...");
        }
        if ($page < 1) {
            return $this->redirectToRoute('home', ['page' => 1]);
        }


        return $this->render('home/home.html.twig', [
            'hikes' => $hikes,
            'form' => $form,
            'currentPage' => $page,
            'maxPage' => $nbMaxPages
        ]);
    }

    #[Route('', name: 'home_redirect')]
    public function redirectHome(): RedirectResponse
    {
        return $this->redirectToRoute('home');
    }
}
