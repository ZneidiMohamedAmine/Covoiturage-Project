<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class AdminController extends AbstractController
{
    #[Route('/api/control_panel', name: 'app_control_panel', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $users = $em->getRepository(User::class)->findAll();
        $userArray = [];

        foreach ($users as $user) {
            $userArray[] = [
                'Firstname' => $user->getFirstName(),
                'Lastname' => $user->getLastName(),
                'Gender' => $user->getGender(),
                'DriverLicense' => $user->isDriverLisence(),
                'Email' => $user->getEmail(),
                'id' => $user->getId(),
            ];
        }

        return new JsonResponse(['users' => $userArray]);
    }

    #[Route('/api/banned', name: 'app_control_panel', methods: ['GET'])]
    public function banned(EntityManagerInterface $em): JsonResponse
    {


        return new JsonResponse(['users' => 'm']);
    }
}
