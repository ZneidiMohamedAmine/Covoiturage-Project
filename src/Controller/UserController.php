<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment; // Assuming User entity exists
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserController extends AbstractController
{
    #[Route('/', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request): Response
    {
        $error = [];
        
        if ($request->isMethod('POST')) {
            // Handle login form submission
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            
            // Perform authentication (you should use Symfony's security component for real projects)
            // Example: $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);
            // Validate user (example only, replace with proper authentication logic)
            if ($username === 'admin' && $password === 'password') {

                
                return $this->redirectToRoute('profile');
            } else {
                $error[] = "Invalid username or password.";
            }
        }
        
        // Render login page with errors
        return $this->render('home/index.html.twig', [
            'error' => $error,
        ]);
    }
    
    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {


        // Redirect to login page
        return $this->redirectToRoute('app_login');
    }
    
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $error = [];

        if ($request->isMethod('POST')) {
            // Handle registration form submission
            $firstname = $request->request->get('firstname');
            $lastname = $request->request->get('lastname');
            $email = $request->request->get('email');
            $birthdate = new \DateTime($request->request->get('birthdate'));
            $gender = $request->request->get('gender');
            $role = $request->request->get('role');
            $driverLicense = (bool) $request->request->get('driver_license');
            $cin = $request->request->get('cin');
            $address = $request->request->get('address');
            $password = $request->request->get('password'); // Ensure password hashing in production

            try {
                // Create new User entity
                $user = new User();
                $user->setCin($cin);
                $user->setFirstName($firstname);
                $user->setLastName($lastname);
                $user->setBirthDate($birthdate);
                // Assuming Address entity exists and is properly handled
                // Replace with actual logic to find or create Address entity
                $user->setAddress($address); // Example assuming Address is a string

                $user->setGender($gender);
                $user->setDriverLicense($driverLicense);
                $user->setRole($role);
                $user->setPhotoAdress('default.jpg'); // Example default photo address
                $user->setPassword($password); // Ensure password hashing in production
                // Example comment entity (assuming Comment entity exists and is properly handled)
                $user->setCommentID(new Comment()); // Replace with proper logic for Comment

                // Persist user to database
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirect to profile page on successful registration
                return $this->redirectToRoute('profile');
            } catch (\Exception $e) {
                $error[] = "Failed to register user: " . $e->getMessage();
            }
        }

        // Render registration page with errors
        return $this->render('home/register.html.twig', [
            'error' => $error,
        ]);
    }
}
?>