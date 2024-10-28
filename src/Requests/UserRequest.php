<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

class UserRequest extends BaseRequest
{

    #[NotBlank([])]
    #[Length(
        min: 6,
        max: 20,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your  password cannot be longer than {{ limit }} characters',)]
    #[Required()]
    protected $password;

    #[Required()]
    #[NotBlank([])]
    protected $username;

    #[Required()]
    #[NotBlank([])]
    protected $roles;
}
