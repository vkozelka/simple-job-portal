<?php

namespace App\System;

use App\System\Form\FieldsNotProvidedException;
use App\System\Form\InvalidActionException;
use App\System\Form\ValidatorsNotProvidedException;
use App\System\Mvc\Model\Entity;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

class Form
{

    const FORM_ACTION_CREATE = "create";
    const FORM_ACTION_UPDATE = "update";

    private $__action = null;
    private $__entity = null;

    private $__errors = [];

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $__validator;

    private $__data = [];

    public function __construct($action = Form::FORM_ACTION_CREATE, Entity $entity = null)
    {
        $this->__validator = Validation::createValidator();
        if (!in_array($action, [
            Form::FORM_ACTION_CREATE,
            Form::FORM_ACTION_UPDATE
        ])) {
            throw new InvalidActionException("Action must be create or update");
        }
        $this->__action = $action;
        $this->__entity = $entity;
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private function getValidator()
    {
        return $this->__validator;
    }

    public function getFields()
    {
        throw new FieldsNotProvidedException();
    }

    public function getValidators()
    {
        throw new ValidatorsNotProvidedException();
    }

    public function getAction()
    {
        return $this->__action;
    }

    public function getEntity()
    {
        return $this->__entity;
    }

    public function isValid(array $data = [])
    {
        $valid = true;

        foreach ($this->getFields() as $field) {
            $value = App::get()->getRequest()->request->get($field, null);
            if (isset($this->getValidators()[$field])) {
                $this->__errors[$field] = $this->getValidator()->validate($value, $this->getValidators()[$field]);
                if (count($this->__errors[$field])) {
                    $valid = false;
                }
            }
        }

        return $valid;
    }

    public function addError($field, ConstraintViolation $error)
    {
        $this->__errors[$field]->add($error);
    }

    public function getErrors($field = null)
    {
        if (!$field) {
            return $this->__errors;
        }
        return $this->__errors[$field];
    }

    public function hasErrors($field = null)
    {
        if ($field) {
            if (isset($this->__errors[$field]) && count($this->__errors[$field])) {
                return true;
            } else {
                return false;
            }
        } else {
            $count = 0;
            foreach ($this->__errors as $field => $errors) {
                $count += count($errors);
            }
            return $count > 0 ? true : false;
        }
    }

    public function getValues()
    {
        $result = new ParameterBag();
        foreach ($this->getFields() as $field) {
            $result->set($field, App::get()->getRequest()->request->get($field));
        }
        return $result;
    }

    public function setData(array $data = [])
    {
        foreach ($this->getFields() as $field) {
            if (isset($data[$field])) {
                if (substr($field, 0, 3) == "is_") {
                    // Boolean
                    if ($data[$field]) {
                        $this->__data[$field] = $data[$field];
                    }
                } else {
                    // Other
                    $this->__data[$field] = $data[$field];
                }
            }
        }
        return $data;
    }

    public function hasData(string $field)
    {
        return isset($this->__data[$field]) ? true : false;
    }

    public function getData(string $field)
    {
        if ($this->hasData($field)) {
            return $this->__data[$field];
        }
        return null;
    }

}