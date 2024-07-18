<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Trajet;
use App\Entity\User;
use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trip = $entityManager->getRepository(Trajet::class); // For example, fetching trip with ID 1

        
        
        if (!$trip) {
            throw $this->createNotFoundException('No trip found for id 1');
        }
        

        // Trip data array
        $tripArray = $trip->findAllLessThanToday();
        $Addressrep = $entityManager->getRepository(Address::class);
        $tripDetails = [];
        foreach ($tripArray as $trip) {
            $debutAddress = $Addressrep->find($trip->getDebut());
            $destinationAddress = $Addressrep->find($trip->getDestination());

            $tripDetails[] = [
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

        
        return $this->render('Pages/index.html.twig', [
            'trajets' => $tripDetails,
        ]);
    }
}


