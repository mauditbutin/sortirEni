<?php

namespace App\Controller\Api;

use App\Entity\City;
use App\Form\LocationCreateType;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LocationController extends AbstractController
{
    #[Route('/location/addAjax', name: 'api_location_addAjax')]
    public function addAjax(
        EntityManagerInterface $manager,
        Request $request,
        CityRepository $cityRepository
    ): JsonResponse
    {
        $location = new \App\Entity\Location();
        $locationForm = $this->createForm(LocationCreateType::class, $location);
        $locationForm->handleRequest($request);

        if($locationForm->isSubmitted() && $locationForm->isValid()) {
            $city = $cityRepository->findOneBy(
                ['zipcode' => $locationForm->get('zipcode')->getData(),
                    'name' => $locationForm->get('city')->getData()
                ]);
            if ($city) {
                $location->setCity($city);
            } else {
                $newCity = new City();
                $newCity->setName($locationForm->get('city')->getData());
                $newCity->setZipcode($locationForm->get('zipcode')->getData());
                $manager->persist($newCity);
                $manager->flush();
                $location->setCity($newCity);
            }
            $manager->persist($location);
            $manager->flush();

            return $this->json([
                'success' => true,
                'id' => $location->getId(),
                'name' => $location->getName(),
            ]);

        }

//        $errors = [];
//
//        foreach ($locationForm->getErrors(true) as $error) {
//            $field = $error->getOrigin()->getName();
//            $errors[$field][] = $error->getMessage();
//        }

        return $this->json([
            'success' => false,
//            'errors' => $errors
        ], 400);
    }

    #[Route('/location/byCity/{id}',
        name: 'api_location_getLocationsByCity')]
    public function getLocationsByCity(
        LocationRepository $locationRepository,
        int $id=null)
    {
        $locations = $locationRepository
            ->getLocationsByCity($id);
        return $this->json($locations,
            Response::HTTP_OK, [], ['groups' => 'location_api']);
    }

    #[Route('/location/infos/{id}', name: 'api_location_getInfos')]
    public function getLocationInfos(
        LocationRepository $locationRepository,
        int $id=null)
    {
        $locations = $locationRepository->getLocationInfosById($id);
        return $this->json($locations, Response::HTTP_OK, [], ['groups' => 'location_api']);
    }
}
