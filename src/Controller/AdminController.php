<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class AdminController extends AbstractController
{
    #[Route('/api/control_panel', name: 'app_control_panel' , methods: ['GET','POST'])]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $em->getRepository(User::class)->findAll();

        $userArray = [];

        foreach ($users as $user) {
            
            $userArray = [
                'Firstname' => $user->getFirstName(),
                'Lastname' => $user->getLastName(),
                'Gender' => $user->getGender(),
                'DriverLicense' => $user->isDriverLisence(),
                'Email' => $user->getEmail(),
            ];
        }
        return $this->render('Pages/controlpanel.html.twig', [
            'controller_name' => 'AdminController','users' => $users,
        ]);
    }
}
