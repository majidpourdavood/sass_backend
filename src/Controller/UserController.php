<?php

namespace App\Controller;


use App\Entity\Place;
use App\Entity\User;
use App\Requests\PlaceRequest;
use App\Requests\UserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{
    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
//        $getId = $this->getUser()->getId();
        $users = $entityManager->getRepository(User::class)->findAll();
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ];
        }
        return responseJson("success", 200, "Get All User.", null, $data);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function create(EntityManagerInterface      $entityManager, Request $request,
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


    /**
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {

        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return responseJson("error", 404, "User Not Found.", null, null);
        }
        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];

        return responseJson("success", 200, "Get User.", null, $data);
    }

    public function update(EntityManagerInterface $entityManager,
                           Request $request, int $id,
                           UserPasswordHasherInterface $passwordHasher, SerializerInterface $serializer
    ): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return responseJson("error", 404, "User Not Found.", null, null);
        }

        $requestData = $request->getContent();
        $userData = $serializer->deserialize($requestData, User::class, 'json');

        $username = $userData->getUserName();
        $roles = $userData->getRoles();

        $password = $userData->getPassword();
        if (isset($password)){
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);
        }

        $user->setUsername($username);
        $user->setRoles($roles);
        $user->setPassword($hashedPassword);
        $entityManager->flush();

        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];

        return responseJson("success", 200, "Update User.", null, $data);
    }

    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return responseJson("error", 404, "User Not Found.", null, null);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return responseJson("success", 200, "Deleted User.", null, $user);
    }

}