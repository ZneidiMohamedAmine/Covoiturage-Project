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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route("/api", name: 'api_')]
class ProfileController extends AbstractController
{
    private $jwtManager;
    private $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }
    
    #[Route('/profile', name: 'app_profile' )]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        if ($request->headers->get('Content-Type') === 'application/json') {
            $data = json_decode($request->getContent(), true);
            }

           


        $profileid= $data['idprofile'] ?? null;
        

        
    
        $userRepository = $entityManager->getRepository(User::class);

         if($profileid === null)
         {
            $user = $this->getUser();
         }
         else
         {
            $user = $userRepository->find($profileid);
         }
         
         
         
        
       // $user = $this->getUser();
           
        /** @var User $user */

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        /** @var User $authuser  */ $authuser = $this->getUser();

        $userArray = [
            'currentuser' => $user->getId(),
            'authuser' => $authuser->getId(),
            'Firstname' => $user->getFirstName(),
            'Lastname' => $user->getLastName(),
            'Gender' => $user->getGender(),
            'DriverLicense' => $user->isDriverLisence(),
        ];

        $tripRepository = $entityManager->getRepository(Trajet::class);
        $tripArray = $tripRepository->findAllLessThanToday();
        $tripcreated = $tripRepository->findAllCreated((int)$user->getId());
        $tripJoined = $tripRepository->findAllJoined((int)$user->getId());
        $tripcurrentcreated = $tripRepository->findAllcurrentCreated((int)$user->getId());
        $tripcurrentJoined = $tripRepository->findAllcurrentJoined((int)$user->getId());

        $addressRepository = $entityManager->getRepository(Address::class);

        $CommentRepository = $entityManager->getRepository(Comment::class);
        $CommentArray = $CommentRepository->findAllCommentrelated($user->getId());

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
                    'trajetid' => $trip->getId(),
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
                    'trajetid' => $trip->getId(),
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

        $tripDetailsCurrentCreated = [];

        foreach ($tripcurrentcreated as $trip) {
            $debutAddress = $addressRepository->find($trip->getDebut());
            $destinationAddress = $addressRepository->find($trip->getDestination());

            if ($debutAddress && $destinationAddress) {
                $tripDetailsCurrentCreated[] = [
                    'trajetid' => $trip->getId(),
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

        $tripDetailsCurrentJoined = [];

        foreach ($tripcurrentJoined as $trip) {
            $debutAddress = $addressRepository->find($trip->getDebut());
            $destinationAddress = $addressRepository->find($trip->getDestination());

            if ($debutAddress && $destinationAddress) {
                $tripDetailsCurrentJoined[] = [
                    'trajetid' => $trip->getId(),
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
            dump($commenterid);
            /**
             * @var User $user
             */
            $user = $userRepository->find($commenterid);
            $username = $user->getFirstname();
            $userlast = $user->getLastname();
            
            

           
                $comments[] = [
                    'commentId' => $comment->getId(),
                    'commenterId' => $user->getId(),
                    'Stars' => $comment->getStarsNumber(),
                    'Description' => $comment->getDescription(),
                    'commentername' => $username,
                    'commenterLastname' => $userlast
                ];
            
        }

        return new JsonResponse(['userinfo' => $userArray,
            'tripcreated' => $tripDetailsCreated,
            'tripjoined' => $tripDetailsJoined,
            'tripcurrentcreated' => $tripDetailsCurrentCreated,
            'tripcurrentjoined' => $tripDetailsCurrentJoined,
            'comments' => $comments,], Response::HTTP_OK);  
            
        
    }

    #[Route('/comment', name: 'app_comment_profile')]
    public function comment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);

        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $commenteduser =  $data['profileId'] ?? null;

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

        $nbrstar = htmlspecialchars($data['stars'] ?? null);
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
        

        //$id = $data['id'] ?? null;
        $id = 1;


        $CommentRepository = $entityManager->getRepository(Comment::class);
        $userRepository = $entityManager->getRepository(User::class);
        
        $user = $this->getUser();
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

    #[Route('/comment/modifier', name: 'app_comment_modifier_profile')]
    public function modifiercomment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        
        dump($data);
        $id = $data['commentId'] ?? null;


        $CommentRepository = $entityManager->getRepository(Comment::class);
        $userRepository = $entityManager->getRepository(User::class);
        
        $user = $this->getUser();
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
