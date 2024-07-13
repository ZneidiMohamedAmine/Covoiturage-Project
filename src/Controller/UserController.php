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
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    
    #[Route('/login', name: 'app_login', methods: ['POST','GET'])]
    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $firstname = $request->request->get('email');
            $lastname = $request->request->get('password');

            if (1 == 1) $this->redirectToRoute('app_profile');
        }
        return $this->render("home/login.html.twig");
        

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
                $user->setDriverLisence($driverLicense);
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
