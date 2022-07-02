<?php
    class Authentication
    {
        private $connection;
        private $tableAuth = "usuarios";
        public $user;
        public $password;
        public $result;

        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function auth()
        {   
            $this->user = htmlspecialchars(strip_tags($this->user));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $passwordEncrypt = hash('sha256',$this->password);
            $sql = "SELECT usuario, clave FROM ".$this->tableAuth." WHERE usuario = '".$this->user."' AND clave = '".$passwordEncrypt."'";
            $this->result = $this->connection->query($sql);
            return $this->result;
        }
    }
?>