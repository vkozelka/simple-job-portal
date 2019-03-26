<?php
namespace App\Module\Core\Form\Validator\ConstraintValidator;

use App\System\App;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DbUnique extends ConstraintValidator {

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof \App\Module\Core\Form\Validator\Constraint\DbUnique) {
            throw new UnexpectedTypeException($constraint, \App\Module\Core\Form\Validator\Constraint\DbUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($constraint->nospaces) {
            $value = preg_replace("/\s/","",$value);
        }

        $query = "SELECT * FROM `%s` WHERE `%s` = '%s'";
        if ($constraint->additionalSql) {
            $query.= $constraint->additionalSql;
        }


        $result = App::get()->getDatabase()->getConnection()->query(sprintf($query, $constraint->table,$constraint->column, $value))->fetch(\PDO::FETCH_ASSOC);

        if ($result && $result[$constraint->column]) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }

        return;
    }

}