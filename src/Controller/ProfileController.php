<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Comment;
use App\Entity\Trajet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile' )]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        //$userId = 4;
        $userId = $request->query->get('userId');
       
        
         $user = $userRepository->find($userId);
        
       // $user = $this->getUser();
           
        /** @var User $user */

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

        $CommentRepository = $entityManager->getRepository(Comment::class);
        $CommentArray = $CommentRepository->findAllCommentrelated(1);

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

        $comments = [];

        foreach ($CommentArray as $comment) {
            $commenterid = $comment->getCommenterId();
            $user = $userRepository->find($commenterid);
            $username = $user->getFirstname();
            $userlast = $user->getLastname();
            

           
                $comments[] = [
                    'Stars' => $comment->getStarsNumber(),
                    'Description' => $comment->getDescription(),
                    'commentername' => $username,
                    'commenterLastname' => $userlast
                ];
            
        }

        return $this->render('home/profile.html.twig', [
            'userinfo' => $userArray,
            'tripcreated' => $tripDetailsCreated,
            'tripjoined' => $tripDetailsJoined,
            'comments' => $comments,
        ]);
    }

    #[Route('/profile/comment', name: 'app_comment_profile')]
    public function comment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = 2;
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $commenteduser = $villedebut = $data['commenteduserid'] ?? null;

        if ($commenteduser == null) {
            throw $this->createNotFoundException('User not found');
        }

        $usercommentedid = $userRepository->find($commenteduser);

        if (!$usercommentedid) {
            throw $this->createNotFoundException('User not found');
        }

        if ($usercommentedid == $user) {
            return new JsonResponse(['error' => 'Can t comment yourself'], Response::HTTP_BAD_REQUEST);
        }

        $nbrstar = htmlspecialchars($data['nbrstar'] ?? null);
        $description = htmlspecialchars($data['description'] ?? null);

        $comment = new Comment();
        $comment->setDescription($description);
        $comment->setStarsNumber($nbrstar);
        $comment->setCommentedId($usercommentedid);
        $comment->setCommenterId($user);
        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Comment created successfully'], Response::HTTP_CREATED);


    }
    #[Route('/profile/comment/supprimer', name: 'app_comment_delete_profile')]
    public function deletecomment(Request $request, EntityManagerInterface $entityManager): Response
    {

        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        

        $id = $data['id'] ?? null;


        $CommentRepository = $entityManager->getRepository(Comment::class);
        $userRepository = $entityManager->getRepository(User::class);
        
        $user = $userRepository->find(2);
        $commentodelete = $CommentRepository->find($id);   

        if($commentodelete->getcommenterId() == $user)
        {
            $entityManager->remove($commentodelete);
            $entityManager->flush();
    
            return new JsonResponse(['success' => 'Comment deleted successfully'], Response::HTTP_CREATED);
        }
        else
        {
            return new JsonResponse(['error' => 'comment failed to delete ' ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/profile/comment/modifier', name: 'app_comment_modifier_profile')]
    public function modifiercomment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        

        $id = $data['id'] ?? null;


        $CommentRepository = $entityManager->getRepository(Comment::class);
        $userRepository = $entityManager->getRepository(User::class);
        
        $user = $userRepository->find(2);
        $commentomodifier = $CommentRepository->find($id);

        $nbrstar = htmlspecialchars($data['nbrstar'] ?? null);
        $description = htmlspecialchars($data['description'] ?? null);

        if($commentomodifier->getcommenterId() == $user)
        {

        $commentomodifier->setDescription($description);
        $commentomodifier->setStarsNumber($nbrstar);
        $entityManager->persist($commentomodifier);
        $entityManager->flush();
        return new JsonResponse(['success' => 'Comment modifier successfully'], Response::HTTP_CREATED);
    }
    else
    {
        return new JsonResponse(['error' => 'comment n est pas modifier ' ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    }
}
