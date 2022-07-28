<?php
class Authentication
{
    private $connection;
    private $tablaUsuarios = "usuarios";
    private $tablaDatosUsuario = "datos_del_usuario";
    private $tablaTokenPersonal = "token_personal";
    public $email;
    public $uid;
    public $password;
    public $token;
    public $result;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function login()
    {
        $passwordEncrypt = hash('sha256', $this->password);
        $sql = "SELECT * FROM " . $this->tablaUsuarios . " WHERE correo = '" . $this->email . "' AND clave = '" . $passwordEncrypt . "'";
        $this->result = mysqli_query($this->connection, $sql);
        if (mysqli_num_rows($this->result) > 0) {
            $dataUser = mysqli_fetch_assoc($this->result);
            $ntoken = $this->createToken();
            $this->uid = $dataUser['uid'];

            if($this->verifyPersonalToken()) $this->updateToken($ntoken); 
            else $this->insetToken($ntoken);

            return array([
                'authenticated' => true,
                'uid' => $dataUser['uid'],
                'token' => $ntoken,
            ]);
        } else return false;
    }

    public function logout()
    {
        return mysqli_query($this->connection, "UPDATE " . $this->tablaTokenPersonal . " SET token = null WHERE uid = " . $this->uid);
    }

    public function verifyPersonalToken()
    {
        if(mysqli_num_rows(mysqli_query($this->connection, "SELECT COUNT(*) FROM " . $this->tablaTokenPersonal)) > 0) return mysqli_num_rows(mysqli_query($this->connection, "SELECT * FROM " . $this->tablaTokenPersonal . " WHERE uid = " . $this->uid)) > 0 ? 1 : 0;
        else return false; 
    }

    public function insetToken($token)
    {
        return mysqli_query($this->connection, "INSERT INTO " . $this->tablaTokenPersonal . " (uid, token) VALUES('" . $this->uid . "', '" . $token . "')");
    }

    public function updateToken($token)
    {
        return mysqli_query($this->connection, "UPDATE " . $this->tablaTokenPersonal . " SET token = '".$token . "' WHERE uid = ".$this->uid);
    }

    public function createToken()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 65; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}