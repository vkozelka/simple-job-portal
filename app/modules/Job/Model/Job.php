<?php
namespace App\Module\Job\Model;

use App\System\Mvc\Model;

class Job extends Model {

    protected $_tableName = "job";

    protected $_entityClass = ""; // Mandatory!

    protected $_primaryKey = "id";



    public function findAllJobs() {
        $statement = $this->query()
            ->select("*")
            ->where("is_active = 1 ")
            ->from($this->_tableName)
            // set fetch mode is mandatory!!
            ->execute();
        return $statement->fetchAll();

    }
}