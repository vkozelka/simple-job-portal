<?php
namespace App\Module\User\Form;

use App\System\Form;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordForm extends Form {

    public function getFields()
    {
        return [
            "old_password",
            "new_password",
            "new_password_confirmation",
        ];
    }

    public function getValidators()
    {
        return [
            "old_password" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Length(["min" => 8])
            ],
            "new_password" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Length(["min" => 8])
            ],
            "new_password_confirmation" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Length(["min" => 8])
            ]
        ];
    }

}