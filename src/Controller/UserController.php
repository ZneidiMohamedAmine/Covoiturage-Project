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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;





class UserController extends AbstractController 


{

    private $jwtManager;
    private $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    

    
    #[Route('/login', name: 'app_login', methods: ['POST','GET'])]
    public function login(Request $request, EntityManagerInterface $entityManager, Security $security,TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {
        $error = []; // Initialize an empty array for errors
    
        // Check if the request content type is JSON
        if ($request->headers->get('Content-Type') === 'application/json') {
            $data = json_decode($request->getContent(), true);
    
            if ($data === null) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }
    
            // Access email and password from the data array
            $email = isset($data['email']) ? $data['email'] : '';
            $password = isset($data['password']) ? $data['password'] : '';

    
         
    
        if (!$email || !$password) {
            $error[] = 'Missing email or password in request.';
            return $this->render("Pages/login.html.twig", ['error' => $error]);
        }
    
        // Hashing logic (replace with your password hashing logic)
        //$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email, 'password' => $password]);
        
    
        if ($user) {
            $token = $this->jwtManager->create($user);
            return new JsonResponse(['token' => $token], Response::HTTP_OK);
        } else {
            return new JsonResponse(['error' => ['Invalid email or password.']], Response::HTTP_BAD_REQUEST);
        }
    }

    return $this->render("Pages/login.html.twig", ['error' => $error]);
    }

    
    #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security): Response
    {
        $response = $security->logout(false);
        return $this->redirectToRoute('app_home');
    }

  

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $error = [];

        if ($request->headers->get('Content-Type') === 'application/json') {
        $data = json_decode($request->getContent(), true);
    
        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);    
    }

        
            $firstname = $data['firstname'] ?? '';
            $lastname = $data['lastname'] ?? '';
            $email = $data['email'] ?? '';
            $birthdate = new \DateTime($data['birthdate']);
            $gender = $data['gender'] ?? '';
            $role = $data['role'] ?? '';
            $driverLicense = (bool) $data['driver_license'];
            $cin = $data['cin'] ?? '';
            $address = $data['address'] ?? '';
            $password = $data['password'] ?? '';

            try {
                // Create new User entity
                $user = new User();
                $user->setCin($cin);
                $user->setFirstName($firstname);
                $user->setLastName($lastname);
                $user->setBirthDate($birthdate);
                $user->setEmail($email);
                $user->setAddress($address); // Assuming Address is a string
                $user->setGender($gender);
                $user->setDriverLisence($driverLicense);
                $user->setPhotoAdress('default.jpg');

                // Hash the password before setting it
                //$hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($password);

                // Persist user to database
                $entityManager->persist($user);
                $entityManager->flush();

                $token = $this->jwtManager->create($user);
                return new JsonResponse(['token' => $token], Response::HTTP_OK);                
            } catch (\Exception $e) {
                $error[] = "Failed to register user: " . $e->getMessage();
                return new JsonResponse(['message' => 'Fail'], Response::HTTP_FAILED_DEPENDENCY);
            }
        }
         

        return $this->render('Pages/register.html.twig', [
            'error' => $error,
        ]);
    }
    #[Route('/user/modifier', name: 'app_register_modifier', methods: [ 'POST','GET'])]
    public function registermodifier(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }


$email = htmlspecialchars($data['email'] ?? '', ENT_QUOTES, 'UTF-8');
$driverLicense = isset($data['driver_license']) ? (bool) htmlspecialchars($data['driver_license'], ENT_QUOTES, 'UTF-8') : null;
$address = htmlspecialchars($data['address'] ?? '', ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($data['password'] ?? '', ENT_QUOTES, 'UTF-8');
$comfirmpassword = htmlspecialchars($data['password'] ?? '', ENT_QUOTES, 'UTF-8');

       // $userId = 2;
        $userRepository = $entityManager->getRepository(User::class);
        /** @var User $user  */
        $user = $this->getUser();

        if($password == $comfirmpassword)
        {
            $user->setEmail($email);
            $user->setDriverLisence($driverLicense);
            $user->setAddress($address);
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            
            return new JsonResponse(['success' => 'Profile modified successfully'], Response::HTTP_OK);
        } else{
            return new JsonResponse(['error' => 'Failed to modify Profile '], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
       

    }
}
