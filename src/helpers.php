<?php

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

if(! function_exists('responseJson')) {
    function responseJson($status = "success",$statusCode = 200, $message = "", $errors = [], $data = [])
    {
        return new JsonResponse([
            "status" =>   $status,
            "message" =>   $message,
            "errors" =>   $errors,
            "data" =>   $data,
        ], $statusCode);

    }
}

