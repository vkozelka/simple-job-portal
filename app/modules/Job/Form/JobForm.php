<?php
namespace App\Module\Job\Form;

use App\Module\Core\Form\Validator\Constraint\DbUnique;
use App\Module\Job\Model\Job;
use App\System\Form;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class JobForm extends Form {

    public function getFields()
    {
        return [
            "title",
            "slug",
            "description",
            "salary_period",
            "salary_type",
            "contract",
            "salary",
            "currency",
            "is_active"
        ];
    }

    public function getValidators()
    {
        return [
            "title" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
            ],
            "slug" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new DbUnique([
                    "table" => "job",
                    "column" => "slug",
                    "additionalSql" => $this->getEntity()?" AND id <> ".$this->getEntity()->id:"",
                    "message" => "Slug {{ value }} je již použitý u jiné pozice, prosím změnte ho."
                ])
            ],
            "description" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
            ],
            "salary_period" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Choice([
                    "choices" => [Job::SALARY_PERIOD_HOUR, Job::SALARY_PERIOD_MONTH]
                ])
            ],
            "salary_type" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Choice([
                    "choices" => [Job::SALARY_TYPE_GROSS, Job::SALARY_TYPE_NET]
                ])
            ],
            "contract" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Choice([
                    "choices" => [Job::CONTRACT_PART_TIME, Job::CONTRACT_OTHER, Job::CONTRACT_FULL_TIME]
                ])
            ],
            "salary" => [
                new NotBlank(["message" => "Toto pole je povinné"]),

            ],
            "currency" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
                new Choice([
                    "choices" => [Job::CURRENCY_CZK, Job::CURRENCY_EUR]
                ])
            ],
            "is_active" => []
        ];
    }

}