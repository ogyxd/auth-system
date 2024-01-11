<?php

namespace Core;

class Database {
    public $instance = null;
    protected $statement = null;

    public function __construct() {
        $dbconfig = config("database");
        extract($dbconfig);
        $dsn = "mysql:host={$host};dbname={$dbname};port={$port}";

        $this->instance = new \PDO($dsn, $user, $pass);
        $this->instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->instance->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function query(string $sql) {
        $this->statement = $this->instance->prepare($sql);
        return $this;
    }

    public function bindParam(string $param, string $value) {
        $this->statement->bindParam($param, $value);
        return $this;
    }

    public function execute() {
        $this->statement->execute();
        return $this;
    }

    public function fetch() {
        $this->statement->execute();
        return $this->statement->fetch();
    }

    public function fetchAll() {
        $this->statement->execute();
        return $this->statement->fetchAll();
    }
}