<?php
namespace App\Module\Core\Entity\Admin;

use App\System\Mvc\Model\Entity;

class User extends Entity {

    public $id_admin_user;
    public $id_admin_user_role = \App\Module\Core\Model\Admin\User\Role::USER_ROLE_ADMIN;
    public $username;
    public $password;
    public $salt;
    public $is_active = 0;
    public $created_at;
    public $updated_at;

}