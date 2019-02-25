<?php
namespace App\Module\User\Form;

use App\Module\Core\Form\Validator\Constraint\DbUnique;
use App\System\Form;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class RegisterForm extends Form {

    public function getFields()
    {
        return [
            "first_name",
            "last_name",
            "phone",
            "email",
            "address",
            "sex",
            "bank_account_number",
            "personal_identification_number",
            "gdprinfo"
        ];
    }

    public function getValidators()
    {
        return [
            "first_name" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
            ],
            "last_name" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
            ],
            "email" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Email(),
                new DbUnique([
                    "table" => "user",
                    "column" => "email",
                    "message" => "Email {{ value }} is taken by other user. Please select different."
                ])
            ],
            "phone" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
            ],
            "address" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
            ],
            "sex" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Choice([
                    "choices" => ["male", "female"]
                ])
            ],
            "bank_account_number" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new DbUnique([
                    "table" => "user",
                    "column" => "bank_account_number",
                    "message" => "The bank account: {{ value }} is taken by other user. Please select different."
                ])
            ],
            "personal_identification_number" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new DbUnique([
                    "table" => "user",
                    "column" => "personal_identification_number",
                    "message" => "The identification number: {{ value }} is taken by other user. Please select different."
                ])
            ],
            "gdprinfo" => [
                new NotNull(["message" => "Musíte souhlasit s obchodními podmínkami."])
            ]
        ];
    }

}