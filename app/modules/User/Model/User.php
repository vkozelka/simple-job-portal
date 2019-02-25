<?php
namespace App\Module\User\Model;

use App\System\Mvc\Model;

class User extends Model {

    const USER_SEX_MALE = "male";
    const USER_SEX_FEMALE = "female";

    protected $_tableName = "user";

    protected $_primaryKey = "id_user";

    protected $_entityClass = "\\App\\Module\\User\\Entity\\User";

    public function findAllUsers() {
        $statement = $this->query()
            ->select("*")
            ->from($this->_tableName)
            ->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->_entityClass);
        return $statement->fetchAll();
    }

    public function findPartnersByUser(int $id_user) {
        $statement = $this->query()
            ->select("*")
            ->from($this->_tableName)
            ->where("id_user_parent = ?")
            ->setParameter(0, $id_user)
            ->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->_entityClass);
        return $statement->fetchAll();
    }

}