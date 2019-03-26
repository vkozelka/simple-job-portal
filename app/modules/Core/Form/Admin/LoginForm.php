<?php
namespace App\Module\Core\Form\Admin;

use App\System\Form;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginForm extends Form {

    public function getFields()
    {
        return [
            "username",
            "password"
        ];
    }

    public function getValidators()
    {
        return [
            "username" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Length(["min" => 5])
            ],
            "password" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Length(["min" => 8])
            ]
        ];
    }

}