<?php
namespace App\Module\User\Form;

use App\System\Form;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginForm extends Form {

    public function getFields()
    {
        return [
            "email",
            "password"
        ];
    }

    public function getValidators()
    {
        return [
            "email" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Length(["min" => 5]),
                new Email()
            ],
            "password" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Length(["min" => 8])
            ]
        ];
    }

}