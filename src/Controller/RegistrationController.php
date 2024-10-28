<?php

namespace App\Controller;

use App\Requests\UserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;
use function Lcobucci\JWT\Token\toString;

#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register', methods: 'post')]
    public function index(EntityManagerInterface      $entityManager, Request $request,
                          UserRequest                 $UserRequest,
                          SerializerInterface         $serializer,
                          UserPasswordHasherInterface $passwordHasher): JsonResponse
    {

        $requestData = $request->getContent();
        $userData = $serializer->deserialize($requestData, User::class, 'json');

        $user = new User();
        $username = $userData->getUserName();
        $roles = $userData->getRoles();
        $password = $userData->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setUsername($username);
        $user->setRoles($roles);
        $user->setPassword($hashedPassword);
        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];

        return responseJson("success", 201, "User Created.", null, $data);

    }

    #[Route('/get-clients', name: 'getClients', methods: 'get')]
    public function getUserClient(EntityManagerInterface $entityManager): JsonResponse
    {

        try {
            $users = $entityManager->getRepository(User::class)->findByRole('ROLE_CLIENT');
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'id' => $user->getId(),
                    'username' => $user->getUserName(),
                ];
            }
            return responseJson("success", 200, "Get All Client.", null, $data);

        } catch (\Exception $e) {
            return responseJson("error", 400, "error", null, $e . toString());
        }


    }

}