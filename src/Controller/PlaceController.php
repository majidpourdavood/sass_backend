<?php

namespace App\Controller;


use App\Entity\Place;
use App\Entity\User;
use App\Requests\PlaceRequest;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class PlaceController extends AbstractController
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
        $getId = $this->getUser()->getId();
        $places = $entityManager->getRepository(Place::class)->findAll(
        );
        $data = [];
        foreach ($places as $place) {
            $data[] = [
                'id' => $place->getId(),
                'name' => $place->getName(),
                'lat' => $place->getLat(),
                'lng' => $place->getLng(),
            ];
        }
        return responseJson("success", 200, "Get All Place.", null, $data);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function create(EntityManagerInterface $entityManager, Request $request,
                           PlaceRequest           $PlaceRequest,
                           SerializerInterface    $serializer): JsonResponse
    {

        $requestData = $request->getContent();
        $placeData = $serializer->deserialize($requestData, Place::class, 'json');

        $place = new Place();
        $name = $placeData->getName();
        $lat = $placeData->getLat();
        $lng = $placeData->getLng();
        $user = $this->getUser();
        $place->setUser($user);
        $place->setName($name);
        $place->setLat($lat);
        $place->setLng($lng);
        $entityManager->persist($place);
        $entityManager->flush();

        $data = [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'lat' => $place->getLat(),
            'lng' => $place->getLng(),
        ];

        return responseJson("success", 201, "Place Created.", null, $data);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {

        $place = $entityManager->getRepository(Place::class)->find($id);

        if (!$place) {
            return responseJson("error", 404, "Place Not Found.", null, null);
        }
        $data = [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'lat' => $place->getLat(),
            'lng' => $place->getLng(),
        ];

        return responseJson("success", 200, "Get Place.", null, $data);
    }

    public function update(EntityManagerInterface $entityManager,
                           Request                $request, int $id, PlaceRequest $PlaceRequest, SerializerInterface $serializer
    ): JsonResponse
    {
        $place = $entityManager->getRepository(Place::class)->find($id);

        if (!$place) {
            return responseJson("error", 404, "Place Not Found.", null, null);
        }

        $requestData = $request->getContent();
        $placeData = $serializer->deserialize($requestData, Place::class, 'json');

        $name = $placeData->getName();
        $lat = $placeData->getLat();
        $lng = $placeData->getLng();
        $place->setName($name);
        $place->setLat($lat);
        $place->setLng($lng);

        $entityManager->flush();

        $data = [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'lat' => $place->getLat(),
            'lng' => $place->getLng(),
        ];

        return responseJson("success", 200, "Update Place.", null, $data);
    }

    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $place = $entityManager->getRepository(Place::class)->find($id);

        if (!$place) {
            return responseJson("error", 404, "Place Not Found.", null, null);
        }

        $entityManager->remove($place);
        $entityManager->flush();

        return responseJson("success", 200, "Deleted Place.", null, $place);
    }

}