<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Trajet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route("/api", name: 'api_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $tripRepository = $entityManager->getRepository(Trajet::class);
        $tripArray = $tripRepository->findAllAfterThanToday(); // Ensure this method exists and works
        $addressRepository = $entityManager->getRepository(Address::class);

        $tripDetails = [];
        foreach ($tripArray as $trip) {
            $debutAddress = $addressRepository->find($trip->getDebut());
            $destinationAddress = $addressRepository->find($trip->getDestination());

            $tripDetails[] = [
                'trajetid' => $trip->getId(),
                'date' => $trip->getDate()->format('Y-m-d'),
                'time' => $trip->getTime()->format('H:i:s'),
                'seatsAvailable' => $trip->getSeatsAvailable(),
                'seatsOccupied' => $trip->getSeatsOccupied(),
                'price' => $trip->getPrice(),
                'debutVille' => $debutAddress ? $debutAddress->getVille() : 'Unknown',
                'debutRue' => $debutAddress ? $debutAddress->getRue() : 'Unknown',
                'destinationVille' => $destinationAddress ? $destinationAddress->getVille() : 'Unknown',
                'destinationRue' => $destinationAddress ? $destinationAddress->getRue() : 'Unknown',
            ];
        }

        return new JsonResponse($tripDetails, Response::HTTP_OK);
    }
}
