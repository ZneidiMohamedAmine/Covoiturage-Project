<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Entity\Address;
use App\Entity\User;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route("/api", name: 'api_')]
class TrajetController extends AbstractController
{
    #[Route('/trajet', name: 'app_cree_trajet', methods: ['POST'])]
    public function creeTrajet(Request $request, EntityManagerInterface $entityManager): Response
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

            // Ensure the user is logged in
            /*$user = $this->getUser();
            if (!$user instanceof UserInterface) {
                return new JsonResponse(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
            }

            if (!$user instanceof User) {
                return new JsonResponse(['error' => 'User is not an instance of App\Entity\User'], Response::HTTP_UNAUTHORIZED);
            }

            $userId = $user->getId();
            if ($userId === null) {
                return new JsonResponse(['error' => 'User ID not found'], Response::HTTP_BAD_REQUEST);
            }*/

            //$user = $this->getUser();
            $userrep= $entityManager->getRepository(User::class);
            $user = $userrep->find(4);
            

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

            // Create new Trajet entity
            $trajet = new Trajet();
            $trajet->setDate($date);
            $trajet->setTime($time);
            $trajet->setSeatsOccupied($seatsoccupied);
            $trajet->setSeatsAvailable($seatsavailable);
            $trajet->setPrice($price);
            $trajet->setDebut($addressDebut); // Set the debut Address entity
            $trajet->setDestination($addressDestination); // Set the destination Address entity
            $trajet->setOwnerId($user); // Set the user ID

            // Persist trajet to database
            $entityManager->persist($trajet);
            $entityManager->flush();

            // Return success response
            return new JsonResponse(['success' => 'Trajet created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to create trajet: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/trajet/modifier', name: 'app_modifier_trajet', methods: ['POST'])]
    public function modifierTrajet(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Retrieval of the ID of the post and check if it is within the ones the user posted
            /** @var User $user */
            $user = $this->getUser();

           $data = json_decode($request->getContent(), true);

           // Ensure the required fields are present
           $trajetId = $data['trajetid'] ?? null;
           if (!$trajetId) {
               return new JsonResponse(['error' => 'Trajet ID not provided'], Response::HTTP_BAD_REQUEST);
           }
       
           try {
               // Retrieve the current user (you may need to adjust this based on your authentication logic)
              
               /** @var User $user */
               
       
               // Retrieve the existing Trajet entity
               $trajetRepository = $entityManager->getRepository(Trajet::class);
               /** @var Trajet $trajet */
               $trajet = $trajetRepository->find($trajetId);
       
               if (!$trajet) {
                   return new JsonResponse(['error' => 'Trajet not found'], Response::HTTP_NOT_FOUND);
               }
       
               // Validate ownership
               if ($trajet->getOwnerId() !== $user) {
                   return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
               }

                       // Count existing reservations for the given trajetId and userId
            $qb = $entityManager->createQueryBuilder();
            $qb->select('COUNT(r.id)')
           ->from(Reservation::class, 'r')
           ->where('r.idtrajet = :trajetId')
           ->setParameter('trajetId', $trajetId);


        $count = $qb->getQuery()->getSingleScalarResult();

        $totalSeatsAvailable = $trajet->getSeatsAvailable() + $trajet->getSeatsOccupied();
        $newSeatsOccupied = (int) $data['seatsoccupied'];

        // Check if the new seats occupied + existing reservations exceed total available seats
        if ($newSeatsOccupied + $count > $totalSeatsAvailable) {
            return new JsonResponse([
                'error' => 'Seats occupied exceed available seats including reservations',
                'count' => $count
            ], Response::HTTP_BAD_REQUEST);
        }

        // Update Trajet entity with new data
        $trajet->setDate(new \DateTime($data['date']));
        $trajet->setTime(new \DateTime($data['time']));
        $trajet->setSeatsOccupied($newSeatsOccupied + $count);
        $trajet->setSeatsAvailable($totalSeatsAvailable - $newSeatsOccupied - $count);
        $trajet->setPrice((float) $data['price']);

        // Update Address entities (if needed)
        $debut = $trajet->getDebut();
        $destination = $trajet->getDestination();

        $debut->setVille($data['villedebut']);
        $debut->setRue($data['ruedebut']);
        $entityManager->persist($debut);

        $destination->setVille($data['villedestination']);
        $destination->setRue($data['ruedestination']);
        $entityManager->persist($destination);

        // Persist changes and flush
        $entityManager->persist($trajet);
        $entityManager->flush();

        // Return success response
        return new JsonResponse(['success' => 'Trajet modified successfully'], Response::HTTP_OK);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'Failed to modify trajet: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    }
    #[Route('/trajet/supprimer', name: 'app_modifier_supprimer', methods: ['DELETE'])]
    public function supprimerTrajet(Request $request, EntityManagerInterface $entityManager): Response
    {
            /** @var User $user */
            //$user = $this->getUser();
            
            $data = json_decode($request->getContent(), true);

    // Ensure the required fields are present
    $trajetId = $data['trajetid'] ?? null;
    if (!$trajetId) {
        return new JsonResponse(['error' => 'Trajet ID not provided'], Response::HTTP_BAD_REQUEST);
    }

    try {
        // Retrieve the current user (you may need to adjust this based on your authentication logic)
        
        $user = $this->getUser();

        // Retrieve the Trajet entity
        $trajetRepository = $entityManager->getRepository(Trajet::class);
        $trajet = $trajetRepository->find($trajetId);

        if (!$trajet) {
            return new JsonResponse(['error' => 'Trajet not found'], Response::HTTP_NOT_FOUND);
        }

        // Validate ownership
        if ($trajet->getOwnerId() !== $user) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Remove Trajet entity
        $entityManager->remove($trajet);
        $entityManager->flush();

        // Return success response
        return new JsonResponse(['success' => 'Trajet deleted successfully'], Response::HTTP_OK);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'Failed to delete trajet: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    }
}
