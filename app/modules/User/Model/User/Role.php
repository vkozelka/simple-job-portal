<?php
namespace App\Module\User\Model\User;

use App\System\Mvc\Model;

class Role extends Model {

    const USER_ROLE_ADMIN = 1;
    const USER_ROLE_CUSTOMER = 2;

    protected $_tableName = "user_role";

    protected $_primaryKey = "id_user_role";

    protected $_entityClass = "\\App\\Module\\User\\Entity\\User\\Role";

}