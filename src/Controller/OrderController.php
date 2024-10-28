<?php

namespace App\Controller;


use App\Entity\Delivery;
use App\Entity\Order;
use App\Entity\Place;
use App\Entity\User;
use App\Requests\OrderRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class OrderController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {

        $deliveries = $entityManager->getRepository(Order::class)->findAll();
        $data = [];
        foreach ($deliveries as $order) {
            $data[] = [
                'id' => $order->getId(),
                'client' => $order->getClient()->getUsername(),
                'delivery' => $order->getDelivery()->getName(),
                'origin' => $order->getOrigin()->getName(),
                'destination' => $order->getDestination()->getName(),
                'amountFuel' => $order->getAmountFuel(),
            ];
        }
        return responseJson("success", 200, "Get All Order.", null, $data);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function create(EntityManagerInterface $entityManager, Request $request,
                           OrderRequest           $OrderRequest,
                           SerializerInterface    $serializer): JsonResponse
    {

        $requestData = $request->getContent();
        $requestDataDecode = json_decode($requestData);

        $deliveryModel = $entityManager->getRepository(Delivery::class)->find( $requestDataDecode->delivery);
        $clientModel = $entityManager->getRepository(User::class)->find( $requestDataDecode->client);
        $originModel = $entityManager->getRepository(Place::class)->find( $requestDataDecode->origin);
        $destinationModel = $entityManager->getRepository(Place::class)->find( $requestDataDecode->destination);
        $amountFuel = $requestDataDecode->amount_fuel;

        $order = new Order();

        $user = $this->getUser();
        $order->setOwner($user);

        $order->setClient($clientModel);
        $order->setDelivery($deliveryModel);
        $order->setOrigin($originModel);
        $order->setDestination($destinationModel);
        $order->setAmountFuel($amountFuel);

        $entityManager->persist($order);
        $entityManager->flush();

        $data = [
            'id' => $order->getId(),
            'client' => $order->getClient()->getUsername(),
            'delivery' => $order->getDelivery()->getName(),
            'origin' => $order->getOrigin()->getName(),
            'destination' => $order->getDestination()->getName(),
            'amountFuel' => $order->getAmountFuel(),
        ];

        return responseJson("success", 201, "Order Created.", null, $data);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     */
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {

        $order = $entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            return responseJson("error", 404, "Order Not Found.", null, null);
        }
        $data = [
            'id' => $order->getId(),
            'client' => $order->getClient()->getUsername(),
            'clientId' => $order->getClient()->getId(),
            'delivery' => $order->getDelivery()->getName(),
            'deliveryId' => $order->getDelivery()->getId(),
            'origin' => $order->getOrigin()->getName(),
            'originId' => $order->getOrigin()->getId(),
            'destination' => $order->getDestination()->getName(),
            'destinationId' => $order->getDestination()->getId(),
            'amountFuel' => $order->getAmountFuel(),
        ];

        return responseJson("success", 200, "Get Order.", null, $data);
    }

    public function update(EntityManagerInterface $entityManager,
       Request $request, int $id, OrderRequest $OrderRequest, SerializerInterface $serializer
    ): JsonResponse
    {
        $order = $entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            return responseJson("error", 404, "Order Not Found.", null, null);
        }

        $requestData = $request->getContent();
        $requestDataDecode = json_decode($requestData);

        $deliveryModel = $entityManager->getRepository(Delivery::class)->find( $requestDataDecode->delivery);
        $clientModel = $entityManager->getRepository(User::class)->find( $requestDataDecode->client);
        $originModel = $entityManager->getRepository(Place::class)->find( $requestDataDecode->origin);
        $destinationModel = $entityManager->getRepository(Place::class)->find( $requestDataDecode->destination);
        $amountFuel = $requestDataDecode->amount_fuel;

        $order->setClient($clientModel);
        $order->setDelivery($deliveryModel);
        $order->setOrigin($originModel);
        $order->setDestination($destinationModel);
        $order->setAmountFuel($amountFuel);

        $entityManager->flush();

        $data = [
            'id' => $order->getId(),
            'client' => $order->getClient()->getUsername(),
            'delivery' => $order->getDelivery()->getName(),
            'origin' => $order->getOrigin()->getName(),
            'destination' => $order->getDestination()->getName(),
            'amountFuel' => $order->getAmountFuel(),
        ];


        return responseJson("success", 200, "Update Order.", null, $data);
    }

    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $order = $entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            return responseJson("error", 404, "Order Not Found.", null, null);
        }

        $entityManager->remove($order);
        $entityManager->flush();

        return responseJson("success", 200, "Deleted Order.", null, $order);
    }

}