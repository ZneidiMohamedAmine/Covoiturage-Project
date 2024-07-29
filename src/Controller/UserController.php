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
use Symfony\Component\Security\Core\User\UserInterface;

#[Route("/api", name: 'api_')]
class UserController extends AbstractController 
{
    private $jwtManager;
    private $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->headers->get('Content-Type') === 'application/json') {
            $data = json_decode($request->getContent(), true);

            if ($data === null) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

           
            

            if (!$email || !$password) {
                return new JsonResponse(['error' => 'Missing email or password in request.'], Response::HTTP_BAD_REQUEST);
            }

            
            $userRepository = $entityManager->getRepository(User::class);
            
            $user = $userRepository->findOneBy(['email' => $email, 'password' => $password]);

            if (!$user) {
                return new JsonResponse(['error' => 'Invalid email or password.'], Response::HTTP_BAD_REQUEST);
            }

            

            $payload = ['name' => 'test', 'email' => $email]; // Adjust payload as needed

            try {
                // Generate JWT token
                $jwt = $this->jwtManager->createFromPayload($user, $payload);
                return new JsonResponse(['jwt' => $jwt, 'message' => 'Login successful'], Response::HTTP_OK);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'invalid'], Response::HTTP_BAD_REQUEST);
            }
        }
        return new JsonResponse('Succes', Response::HTTP_OK);

    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security): Response
    {
        $response = $security->logout(false);
        return new JsonResponse('Succes', Response::HTTP_OK);
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
                $user->setStatus(true);

                // Hash the password before setting it
                //$hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($password);

                // Persist user to database
                $entityManager->persist($user);
                $entityManager->flush();

                $token = $this->jwtManager->create($user);
                return new JsonResponse(['jwt' => $token], Response::HTTP_OK);                
            } catch (\Exception $e) {
                $error[] = "Failed to register user: " . $e->getMessage();
                return new JsonResponse(['message' => 'Fail'], Response::HTTP_FAILED_DEPENDENCY);
            }
        }
         

        return $this->render('Pages/register.html.twig', [
            'error' => $error,
        ]);
    }
    #[Route('/user/modifier', name: 'app_register_modifier', methods: [ 'POST'])]
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

    #[Route('/user/desactive', name: 'app_register_desactive', methods: [ 'POST'])]
    public function registerdesacttiver(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        /** @var User $user  */
        $user = $this->getUser();

        $user->setStatus(false);
        $entityManager->persist($user);
        $entityManager->flush();

        $security->logout(false);
        return $this->redirectToRoute('app_home');
    }
}
