<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route("/api", name: 'api_')]
class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_cree_reservation')]
    public function cree_reservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $trajetId = $data['trajetid'] ?? null;
        if (!$trajetId) {
            return new JsonResponse(['error' => 'Trajet ID not provided'], Response::HTTP_BAD_REQUEST);
        }

        $trajetRepository = $entityManager->getRepository(Trajet::class);
        /** @var Trajet $trajet */
        $trajet = $trajetRepository->find($trajetId);
        $trajetplaceoccupe = $trajet->getSeatsAvailable();

        $user = $this->getUser();

        // Check if the user has already reserved this trajet
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        $existingReservation = $reservationRepository->findOneBy([
            'idtrajet' => $trajetId,
            'iduser' => $user->getUserIdentifier(),
        ]);

        if ($existingReservation) {
            return new JsonResponse(['error' => 'User has already reserved this trajet'], Response::HTTP_CONFLICT);
        }

        if ($trajetplaceoccupe <= 0) {
            return new JsonResponse(['error' => 'Places are full'], Response::HTTP_CONFLICT);
        }


        try {

            $reservation = new Reservation();
            $reservation->setIdtrajet($trajet);
            $reservation->setIduser($user);
            $entityManager->persist($reservation);

            $trajet->setSeatsAvailable($trajet->getSeatsAvailable() - 1);
            $trajet->setSeatsOccupied($trajet->getSeatsOccupied() + 1);
            $entityManager->persist($trajet);




            $entityManager->flush();



            return new JsonResponse(['success' => 'Reservation created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to create reservation: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/reservation/supprimer', name: 'app_supprimer_reservation')]
    public function supprimer_reservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $trajetId = $data['trajetid'] ?? null;
        if (!$trajetId) {
            return new JsonResponse(['error' => 'Trajet ID not provided'], Response::HTTP_BAD_REQUEST);
        }

        $trajetRepository = $entityManager->getRepository(Trajet::class);
        /** @var Trajet $trajet */
        $trajet = $trajetRepository->find($trajetId);


        $user = $this->getUser();

        // Check if the user has already reserved this trajet
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        $existingReservation = $reservationRepository->findOneBy([
            'idtrajet' => $trajetId,
            'iduser' => $user->getUserIdentifier(),
        ]);

        if (!$existingReservation) {
            return new JsonResponse(['error' => 'User is not registred in this trajet'], Response::HTTP_CONFLICT);
        }

        try {

            $entityManager->remove($existingReservation);
            $trajet->setSeatsAvailable($trajet->getSeatsAvailable() + 1);
            $trajet->setSeatsOccupied($trajet->getSeatsOccupied() - 1);

            $entityManager->flush();

            return new JsonResponse(['success' => 'Reservation deleted successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to delete reservation: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
