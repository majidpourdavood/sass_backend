<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class ProductRequest extends BaseRequest
{

    #[Type('integer')]
    #[NotBlank()]
    #[Required()]
    protected $price;

    #[NotBlank([])]
    #[Length(
        min: 2,
        max: 255,
        minMessage: 'Your name must be at least {{ limit }} characters long',
        maxMessage: 'Your  name cannot be longer than {{ limit }} characters',)]
    #[Required()]
    protected $name;

    #[Required()]
    #[Length(
        min: 10,
        max: 255,
        minMessage: 'Your description must be at least {{ limit }} characters long',
        maxMessage: 'Your description cannot be longer than {{ limit }} characters',)]
    #[NotBlank([])]
    protected $description;
}
