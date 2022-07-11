<?php
    class Authentication
    {
        private $connection;
        private $tableAuth = "t_usuarios";
        public $user;
        public $id;
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
            $sql = "SELECT * FROM ".$this->tableAuth." WHERE usuario = '".$this->user."' AND clave = '".$passwordEncrypt."'";
            $this->result = $this->connection->query($sql);
            if($this->result->num_rows > 0){
                $row = $this->result->fetch_assoc();
                $this->id = $row['id_usuario'];
                return true;
            }else return false;
        }

        public function updateToken($token)
        {
            $this->connection->query("UPDATE ".$this->tableAuth." SET token = '".$token."' WHERE id_usuario = ".$this->id);
        }
    }
?>