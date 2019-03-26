<?php
namespace App\Module\News\Form;

use App\Module\Core\Form\Validator\Constraint\DbUnique;
use App\Module\News\Model\News;
use App\System\Form;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsForm extends Form {

    public function getFields()
    {
        return [
            "title",
            "slug",
            "description",
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
            "is_active" => []
        ];
    }

}