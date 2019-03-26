<?php
namespace App\Module\Page\Form;

use App\Module\Core\Form\Validator\Constraint\DbUnique;
use App\Module\Page\Model\Page;
use App\System\Form;
use Symfony\Component\Validator\Constraints\NotBlank;

class PageForm extends Form {

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
                    "message" => "Slug {{ value }} je již použitý u jiné stránky, prosím změnte ho.",
                    "additionalSql" => $this->getEntity()?" AND id <> ".$this->getEntity()->id:""

                ])
            ],
            "description" => [
                new NotBlank(["message" => "Toto pole je povinné"]),
            ],
            "is_active" => []
        ];
    }

}