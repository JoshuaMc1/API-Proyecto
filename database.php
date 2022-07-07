<?php
class Database
{
    public $connection;

    public function getConnection()
    {
        $this->connection = null;
        try {
            $params = include_once("config/db_params.php");
            $this->connection = mysqli_connect($params["db_host"], $params["db_user"], $params["db_password"], $params["db_name"]);
            if (!$this->connection) {
                die("Conexion fallida: " . mysqli_connect_error());
            }
        } catch (Exception $e) {
            echo "Database could not be connected: " . $e->getMessage();
            die();
        }
        return $this->connection;
    }

    public function closeConnection()
    {
        $this->connection->close();
    }
}