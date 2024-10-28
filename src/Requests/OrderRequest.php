<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class OrderRequest extends BaseRequest
{

    #[NotBlank()]
    #[Required()]
    protected $destination;

    #[NotBlank()]
    #[Required()]
    protected $origin;

    #[NotBlank()]
    #[Required()]
    protected $delivery;

    #[NotBlank()]
    #[Required()]
    protected $client;

    #[Required()]
    #[NotBlank([])]
    protected $amount_fuel;
}
