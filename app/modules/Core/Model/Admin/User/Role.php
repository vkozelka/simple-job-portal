<?php
namespace App\Module\Core\Model\Admin\User;

use App\System\Mvc\Model;

class Role extends Model {

    const USER_ROLE_ADMIN = 1;

    protected $_tableName = "admin_user_role";

    protected $_primaryKey = "id_admin_user_role";

    protected $_entityClass = "\\App\\Module\\Core\\Entity\\Admin\\User\\Role";

}