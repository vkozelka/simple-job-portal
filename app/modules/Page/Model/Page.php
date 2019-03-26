<?php
namespace App\Module\Page\Model;

use App\System\Mvc\Model;

class Page extends Model {

    protected $_tableName = "page";

    protected $_entityClass = "\\App\\Module\\Page\\Entity\\Page"; // Mandatory!

    protected $_primaryKey = "id";

    public function findAllPages(bool $onlyActive = true, $sortBy = "created_at", $sortDir = "ASC") {
        $query = $this->query()
            ->select("*")
            ->from($this->_tableName)
            ->orderBy($sortBy, $sortDir);
        if ($onlyActive) {
            $query->where("is_active = 1");
        }
        $statement = $query->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->_entityClass);
        return $statement->fetchAll();
    }
}