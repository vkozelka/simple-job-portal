<?php
namespace App\Module\News\Model;

use App\System\Mvc\Model;

class News extends Model {

    protected $_tableName = "news";

    protected $_entityClass = "\\App\\Module\\News\\Entity\\News"; // Mandatory!

    protected $_primaryKey = "id";

    public function findAllNews(bool $onlyActive = true, $sortBy = "created_at", $sortDir = "ASC") {
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