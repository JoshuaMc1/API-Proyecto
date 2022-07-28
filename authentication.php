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

    /**
     * Si el usuario existe, cree un token, verifique si el usuario tiene un token, si es así,
     * actualícelo, si no, insértelo.
     * 
     * @return una matriz con los siguientes datos:
     */
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

    /**
     * ACTUALIZAR la tabla 'tokenPersonal' ESTABLECER la columna 'token' en nulo DONDE la columna 'uid'
     * es igual al valor de la variable 'uid'
     * 
     * @return El resultado de la consulta.
     */
    public function logout()
    {
        return mysqli_query($this->connection, "UPDATE " . $this->tablaTokenPersonal . " SET token = null WHERE uid = " . $this->uid);
    }

    /**
     * Si la tabla existe y el usuario tiene un token, devuelve 1. Si la tabla existe y el usuario no
     * tiene un token, devuelve 0. Si la tabla no existe, devuelve falso.
     * 
     * @return un valor booleano.
     */
    public function verifyPersonalToken()
    {
        if(mysqli_num_rows(mysqli_query($this->connection, "SELECT COUNT(*) FROM " . $this->tablaTokenPersonal)) > 0) return mysqli_num_rows(mysqli_query($this->connection, "SELECT * FROM " . $this->tablaTokenPersonal . " WHERE uid = " . $this->uid)) > 0 ? 1 : 0;
        else return false; 
    }

    /**
     * Toma un token y lo inserta en una tabla.
     * 
     * @param token El token que desea insertar en la base de datos.
     * 
     * @return El resultado de la consulta.
     */
    public function insetToken($token)
    {
        return mysqli_query($this->connection, "INSERT INTO " . $this->tablaTokenPersonal . " (uid, token) VALUES('" . $this->uid . "', '" . $token . "')");
    }

    /**
     * Actualiza el token en la base de datos.
     * 
     * @param token el token que quiero actualizar
     * 
     * @return El resultado de la consulta.
     */
    public function updateToken($token)
    {
        return mysqli_query($this->connection, "UPDATE " . $this->tablaTokenPersonal . " SET token = '".$token . "' WHERE uid = ".$this->uid);
    }

    /**
     * Crea una cadena aleatoria de 65 caracteres.
     * 
     * @return Una cadena aleatoria de 65 caracteres.
     */
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