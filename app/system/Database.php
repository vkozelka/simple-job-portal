<?php
namespace App\System;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;

class Database {

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $__connection;

    public function __construct()
    {
        App::get()->getProfiler()->start("App::Database::Init");
        $config = App::get()->getConfig()->getConfigValues("database")["mysql"][App::get()->getEnvironment()];
        $this->__connection["default"] = DriverManager::getConnection($config);

        $config = App::get()->getConfig()->getConfigValues("database")["mysql"]["external"];
        $this->__connection["external"] = DriverManager::getConnection($config);
        App::get()->getProfiler()->stop("App::Database::Init");
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection($name = "default")
    {
        return $this->__connection[$name];
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQueryBuilder($connectionName = "default") {
        return $this->getConnection($connectionName)->createQueryBuilder();
    }

}