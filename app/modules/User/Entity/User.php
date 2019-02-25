<?php
namespace App\Module\User\Entity;

use App\Module\User\Model\User\Calling;
use App\System\Mvc\Model\Entity;

class User extends Entity {

    public $id_user;
    public $id_user_role = 2;
    public $id_user_parent;
    public $email;
    public $password;
    public $salt;
    public $first_name;
    public $last_name;
    public $sex = \App\Module\User\Model\User::USER_SEX_MALE;
    public $phone;
    public $personal_identification_number;
    public $bank_account_number;
    public $address;
    public $is_active = 0;
    public $created_at;
    public $updated_at;

    public function getFullGreeting() {
        return sprintf("%s, %s", $this->getGreeting(), $this->getCalling());
    }

    public function getFullName()
    {
        return $this->first_name." ".$this->last_name;
    }

    public function getCalling() {
        $calling = (new Calling())->findFirstBy("name", $this->first_name);
        if ($calling) {
            return $calling->calling;
        }
        return $this->first_name;
    }

    public function getGreeting() {
        if (intval(date("H")) >= 4 && intval(date("H")) < 10) {
            return "Dobré ráno";
        } elseif (intval(date("H")) >= 10 && intval(date("H")) < 13) {
            return "Dobré poledne";
        } elseif (intval(date("H")) >= 13 && intval(date("H")) < 18) {
            return "Dobré odpoledne";
        } elseif (intval(date("H")) >= 18 && intval(date("H")) < 22) {
            return "Dobrý večer";
        }
        return "Dobrou noc";
    }

    public function isAdmin() {
        return intval($this->id_user_role) === \App\Module\User\Model\User\Role::USER_ROLE_ADMIN;
    }

}