<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class PlaceRequest extends BaseRequest
{

    #[NotBlank([])]
    #[Length(
        min: 2,
        max: 255,
        minMessage: 'Your name must be at least {{ limit }} characters long',
        maxMessage: 'Your  name cannot be longer than {{ limit }} characters',)]
    #[Required()]
    protected $name;

    #[Required()]
    #[NotBlank([])]
    protected $lat;

    #[Required()]
    #[NotBlank([])]
    protected $lng;
}
