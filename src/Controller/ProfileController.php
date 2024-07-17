<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = 1;
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $userArray = [
            'Firstname' => $user->getFirstName(),
            'Lastname' => $user->getLastName(),
            'Gender' => $user->getGender(),
            'DriverLicense' => $user->isDriverLisence(),
        ];

        $tripRepository = $entityManager->getRepository(Trajet::class);
        $tripArray = $tripRepository->findAllLessThanToday();
        $tripcreated = $tripRepository->findAllCreated(1);
        $tripJoined = $tripRepository->findAllJoined(1);

        $addressRepository = $entityManager->getRepository(Address::class);

        $tripDetails = [];

        foreach ($tripArray as $trip) {
            $debutAddress = $addressRepository->find($trip->getDebut());
            $destinationAddress = $addressRepository->find($trip->getDestination());

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

        $tripDetailsCreated = [];

        foreach ($tripcreated as $trip) {
            $debutAddress = $addressRepository->find($trip->getDebut());
            $destinationAddress = $addressRepository->find($trip->getDestination());

            if ($debutAddress && $destinationAddress) {
                $tripDetailsCreated[] = [
                    'date' => $trip->getDate()->format('Y-m-d'),
                    'time' => $trip->getTime()->format('H:i:s'),
                    'seatsAvailable' => $trip->getSeatsAvailable(),
                    'seatsOccupied' => $trip->getSeatsOccupied(),
                    'price' => $trip->getPrice(),
                    'debutVille' => $debutAddress->getVille(),
                    'debutRue' => $debutAddress->getRue(),
                    'destinationVille' => $destinationAddress->getVille(),
                    'destinationRue' => $destinationAddress->getRue(),
                ];
            }
        }

        $tripDetailsJoined = [];

        foreach ($tripJoined as $trip) {
            $debutAddress = $addressRepository->find($trip->getDebut());
            $destinationAddress = $addressRepository->find($trip->getDestination());

            if ($debutAddress && $destinationAddress) {
                $tripDetailsJoined[] = [
                    'date' => $trip->getDate()->format('Y-m-d'),
                    'time' => $trip->getTime()->format('H:i:s'),
                    'seatsAvailable' => $trip->getSeatsAvailable(),
                    'seatsOccupied' => $trip->getSeatsOccupied(),
                    'price' => $trip->getPrice(),
                    'debutVille' => $debutAddress->getVille(),
                    'debutRue' => $debutAddress->getRue(),
                    'destinationVille' => $destinationAddress->getVille(),
                    'destinationRue' => $destinationAddress->getRue(),
                ];
            }
        }

        return $this->render('home/profile.html.twig', [
            'userinfo' => $userArray,
            'tripcreated' => $tripDetailsCreated,
            'tripjoined' => $tripDetailsJoined,
        ]);
    }
}
