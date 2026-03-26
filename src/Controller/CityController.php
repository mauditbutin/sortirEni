<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CityController extends AbstractController
{
    #[Route('/api/getCity', name: 'api_getCity', methods: 'GET')]
    public function getCity(
        int $id,
        CityRepository $cityRepository
    ): Response
    {
        $city = $cityRepository->find($id);
        return $this->json($city, Response::HTTP_OK, [], ['groups' => 'city-api']);
    }
}
