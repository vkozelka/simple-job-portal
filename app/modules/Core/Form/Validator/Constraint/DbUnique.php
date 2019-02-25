<?php
namespace App\Module\Core\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class DbUnique extends Constraint {

    public $message = 'Value "{{ value }}" is already taken by other record.';

    public $table;
    public $column;
    public $nospaces = true;

    public function validatedBy()
    {
        return str_replace("Constraint","ConstraintValidator",get_class($this));
    }

}