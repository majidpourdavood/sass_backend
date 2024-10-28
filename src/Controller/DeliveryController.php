<?php

namespace App\Controller;


use App\Entity\Delivery;
use App\Requests\DeliveryRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class DeliveryController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $deliveries = $entityManager->getRepository(Delivery::class)->findAll();
        $data = [];
        foreach ($deliveries as $delivery) {
            $data[] = [
                'id' => $delivery->getId(),
                'name' => $delivery->getName(),
                'capacity' => $delivery->getCapacity(),
            ];
        }
        return responseJson("success", 200, "Get All Delivery.", null, $data);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function create(EntityManagerInterface $entityManager, Request $request,
                           DeliveryRequest           $DeliveryRequest,
                           SerializerInterface    $serializer): JsonResponse
    {

        $requestData = $request->getContent();
        $deliveryData = $serializer->deserialize($requestData, Delivery::class, 'json');

        $delivery = new Delivery();
        $name = $deliveryData->getName();
        $capacity = $deliveryData->getCapacity();
        $user = $this->getUser();
        $delivery->setUser($user);
        $delivery->setName($name);
        $delivery->setCapacity($capacity);
        $entityManager->persist($delivery);
        $entityManager->flush();

        $data = [
            'id' => $delivery->getId(),
            'name' => $delivery->getName(),
            'capacity' => $delivery->getCapacity(),
        ];

        return responseJson("success", 201, "Delivery Created.", null, $data);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {

        $delivery = $entityManager->getRepository(Delivery::class)->find($id);

        if (!$delivery) {
            return responseJson("error", 404, "Delivery Not Found.", null, null);
        }
        $data = [
            'id' => $delivery->getId(),
            'name' => $delivery->getName(),
            'capacity' => $delivery->getCapacity(),
        ];

        return responseJson("success", 200, "Get Delivery.", null, $data);
    }

    public function update(EntityManagerInterface $entityManager,
       Request $request, int $id, DeliveryRequest $DeliveryRequest, SerializerInterface $serializer
    ): JsonResponse
    {
        $delivery = $entityManager->getRepository(Delivery::class)->find($id);

        if (!$delivery) {
            return responseJson("error", 404, "Delivery Not Found.", null, null);
        }

        $requestData = $request->getContent();
        $deliveryData = $serializer->deserialize($requestData, Delivery::class, 'json');

        $name = $deliveryData->getName();
        $capacity = $deliveryData->getCapacity();
        $delivery->setName($name);
        $delivery->setCapacity($capacity);

        $entityManager->flush();

        $data = [
            'id' => $delivery->getId(),
            'name' => $delivery->getName(),
            'capacity' => $delivery->getCapacity(),
        ];

        return responseJson("success", 200, "Update Delivery.", null, $data);
    }

    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $delivery = $entityManager->getRepository(Delivery::class)->find($id);

        if (!$delivery) {
            return responseJson("error", 404, "Delivery Not Found.", null, null);
        }

        $entityManager->remove($delivery);
        $entityManager->flush();

        return responseJson("success", 200, "Deleted Delivery.", null, $delivery);
    }

}