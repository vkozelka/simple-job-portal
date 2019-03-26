<?php
namespace App\Module\Core\Model\Admin;

use App\System\Mvc\Model;

class User extends Model {

    protected $_tableName = "admin_user";

    protected $_primaryKey = "id_admin_user";

    protected $_entityClass = "\\App\\Module\\Core\\Entity\\Admin\\User";

}