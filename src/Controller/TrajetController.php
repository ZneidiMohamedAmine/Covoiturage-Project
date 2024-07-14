<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrajetController extends AbstractController
{
    #[Route('/trajet', name: 'app_trajet', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        
            $data = json_decode($request->getContent(), true);

            if ($data === null) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            try {
                $date = isset($data['date']) ? new \DateTime($data['date']) : null;
                $time = isset($data['time']) ? new \DateTime($data['time']) : null;
                $villedebut = $data['villedebut'] ?? null;
                $villedestination = $data['villedestination'] ?? null;
                $ruedebut = $data['ruedebut'] ?? null;
                $ruedestination = $data['ruedestination'] ?? null;
                $seatsoccupied = isset($data['seatsoccupied']) ? (int)$data['seatsoccupied'] : null;
                $seatsavailable = isset($data['seatsavailable']) ? (int)$data['seatsavailable'] : null;
                $price = isset($data['price']) ? (float)$data['price'] : null;

                if (!$date || !$time || !$villedebut || !$villedestination || !$ruedebut || !$ruedestination || $seatsoccupied === null || $seatsavailable === null || $price === null) {
                    return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
                }

                $addressDebut = new Address();
                $addressDebut->setVille($villedebut);
                $addressDebut->setRue($ruedebut);
                $entityManager->persist($addressDebut);

                $addressDestination = new Address();
                $addressDestination->setVille($villedestination);
                $addressDestination->setRue($ruedestination);
                $entityManager->persist($addressDestination);
                $entityManager->flush();

                $debutId = $addressDebut->getId();
                $destinationId = $addressDestination->getId();

                // Create new Trajet entity
                $trajet = new Trajet();
                $trajet->setDate($date);
                $trajet->setTime($time);
                $trajet->setSeatsOccupied($seatsoccupied);
                $trajet->setSeatsAvailable($seatsavailable);
                $trajet->setPrice($price);
                $trajet->setDebut($addressDebut); // Set the debut Address entity
                $trajet->setDestination($addressDestination); // Set the destination Address entity

                // Persist trajet to database
                $entityManager->persist($trajet);
                $entityManager->flush();

                // Return success response
                return new JsonResponse(['success' => 'Trajet created successfully'], Response::HTTP_CREATED);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Failed to create trajet: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } 
    }

