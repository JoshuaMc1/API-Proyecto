<?php
class Database
{
    public $connection;

    public function getConnection()
    {
        $params = require("config/db_params.php");
        $this->connection = null;
        try {
            $this->connection = new PDO("mysql:host=" . $params['db_host'] . ";dbname=" . $params['db_name'], $params['db_user'], $params['db_password']);
            $this->connection->exec("set names utf8");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Database could not be connected: " . $exception->getMessage();
        }
        return $this->connection;
    }
}
