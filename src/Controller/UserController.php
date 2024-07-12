<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Perform any additional validation as needed

        if ($email === 'admin@yahoo.com' && $password === 'abc') {
            return new JsonResponse(['message' => 'Authentication successful'], JsonResponse::HTTP_OK);
           

        } else {
            return new JsonResponse(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('home/profile.html.twig');
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $error = [];

        if ($request->isMethod('POST')) {
            $firstname = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $email = $request->request->get('email');
            $birthdate = new \DateTime($request->request->get('birthdate'));
            $gender = $request->request->get('gender');
            $role = $request->request->get('role');
            $driverLicense = (bool) $request->request->get('driver_license');
            $cin = $request->request->get('cin');
            $address = $request->request->get('address');
            $password = $request->request->get('password');

            try {
                // Create new User entity
                $user = new User();
                $user->setCin($cin);
                $user->setFirstName($firstname);
                $user->setLastName($lastname);
                $user->setBirthDate($birthdate);
                $user->setAddress($address); // Assuming Address is a string
                $user->setGender($gender);
                $user->setDriverLicense($driverLicense);
                $user->setRole($role);
                $user->setPhotoAdress('default.jpg');

                // Hash the password before setting it
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);

                // Persist user to database
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirect to profile page on successful registration
                return $this->redirectToRoute('app_profile');
            } catch (\Exception $e) {
                $error[] = "Failed to register user: " . $e->getMessage();
            }
        }

        return $this->render('home/register.html.twig', [
            'error' => $error,
        ]);
    }
}
