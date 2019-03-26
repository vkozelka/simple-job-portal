<?php
namespace App\System\Mvc;

use App\System\App;
use App\System\Mvc\Model\Entity;
use App\System\Mvc\Model\RecordNotSavedException;

class Model {

    /**
     * @var string
     */
    protected $_tableName = null;

    /**
     * @var string
     */
    protected $_primaryKey = "id";

    /**
     * @var string
     */
    protected $_entityClass = null;

    /**
     * @var \Doctrine\DBAL\Connection|null
     */
    protected $__connection;

    public function __construct()
    {
        $this->__connection = App::get()->getDatabase()->getConnection();
    }

    public function getEntityClass() {
        return $this->_entityClass;
    }

    public function getTableName()
    {
        return $this->_tableName;
    }

    public function getPrimaryKey() {
        return $this->_primaryKey;
    }

    public function query() {
        return $this->__connection->createQueryBuilder();
    }

    public function findFirst(int $id) {
        $statement = $this->__connection->createQueryBuilder()
            ->select("*")
            ->from($this->_tableName)
            ->where($this->_primaryKey." = ?")
            ->setParameter(0, intval($id))
            ->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->_entityClass);
        return $statement->fetch();
    }

    public function findFirstBy(string $column, $id) {
        $statement = $this->__connection->createQueryBuilder()
            ->select("*")
            ->from($this->_tableName)
            ->where($column." = ?")
            ->setParameter(0, $id)
            ->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->_entityClass);
        return $statement->fetch();
    }

    public function save(Entity $entity) {
        $data = $entity->getData();
        if (is_null($data[$this->_primaryKey])) {
            unset($data[$this->_primaryKey]);
            if (property_exists($entity,"created_at")) {
                if (!isset($data["created_at"]) || empty($data["created_at"])) {
                    $data["created_at"] = date("Y-m-d H:i:s");
                }
            }
            return $this->__connection->insert($this->_tableName, $data);
        } else {
            // update
            if (property_exists($entity,"updated_at")) {
                if (!isset($data["updated_at"]) || empty($data["updated_at"])) {
                    $data["updated_at"] = date("Y-m-d H:i:s");
                }
            }
            return $this->__connection->update($this->_tableName, $data, [$this->_primaryKey => $data[$this->_primaryKey]]);
        }
        throw new RecordNotSavedException();
    }

}