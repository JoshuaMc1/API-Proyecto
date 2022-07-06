<?php
    class Users 
    {
        private $connection;
        private $tableUsers = "info_usuario";
        private $result;

        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function getInfoUsers()
        {
            $sql = "SELECT * FROM $this->tableUsers";
            $this->result = $this->connection->query($sql);
            return $this->result;
        }
    }
?>