<?php
namespace App\Module\Job\Model;

use App\System\Mvc\Model;

class Job extends Model {

    const SALARY_PERIOD_HOUR = "hour";
    const SALARY_PERIOD_MONTH = "month";
    const SALARY_TYPE_NET = "net";
    const SALARY_TYPE_GROSS = "gross";
    const CONTRACT_FULL_TIME = "full_time";
    const CONTRACT_PART_TIME = "part_time";
    const CONTRACT_OTHER = "other";
    const CURRENCY_CZK = "czk";
    const CURRENCY_EUR = "eur";

    protected $_tableName = "job";

    protected $_entityClass = "\\App\\Module\\Job\\Entity\\Job"; // Mandatory!

    protected $_primaryKey = "id";

    public function findAllJobs(bool $onlyActive = true, $sortBy = "created_at", $sortDir = "ASC") {
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