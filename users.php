<?php
include 'authentication.php';
class Users
{
    private $connection;
    private $tableUsers = "usuarios";
    private $tabeDataUsers = "datos_del_usuario";
    private $result;
    private $auth;

    /** 
     * Campos de la tabla de usuarios
     */
    public $uid;
    public $user;
    public $email;
    public $password;
    public $rol;
    public $verifyToken;
    public $verify;

    /**
     * Campos de la tabla datos del usuario
     */
    public $dni;
    public $names;
    public $surnames;
    public $genre;
    public $phone;
    public $address;
    public $profile;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->auth = new Authentication($connection);
    }

    public function getInfoUsers()
    {
        $this->result = mysqli_query($this->connection, "SELECT us.uid, us.usuario, us.correo, CASE WHEN us.rol = 1 THEN 'Cliente' WHEN us.rol = 2 THEN 'Repartidor' WHEN us.rol = 3 THEN 'Administrador' END AS 'rol', us.verificado, dt.dni, dt.nombres, dt.apellidos, dt.telefono, dt.direccion, g.*, dt.perfil FROM " . $this->tableUsers . " us INNER JOIN " . $this->tabeDataUsers . " dt ON us.uid = dt.uid INNER JOIN generos g ON dt.id_genero = g.id WHERE us.status = 1 AND dt.status = 1");
        return $this->result;
    }

    public function searchUser()
    {
        $this->result = mysqli_query($this->connection, "SELECT us.uid, us.usuario, us.correo, CASE WHEN us.rol = 1 THEN 'Cliente' WHEN us.rol = 2 THEN 'Repartidor' WHEN us.rol = 3 THEN 'Administrador' END AS 'rol', us.verificado, dt.dni, dt.nombres, dt.apellidos, dt.telefono, dt.direccion, g.*, dt.perfil FROM " . $this->tableUsers . " us INNER JOIN " . $this->tabeDataUsers . " dt ON us.uid = dt.uid INNER JOIN generos g ON dt.id_genero = g.id WHERE us.uid = " . $this->uid . " AND us.status = 1 AND dt.status = 1");
        return $this->result;
    }

    public function create()
    {
        $this->uid = $this->auth->createNumericToken();
        $this->user = htmlspecialchars(strip_tags($this->user));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->names = htmlspecialchars(strip_tags($this->names));
        $this->surnames = htmlspecialchars(strip_tags($this->surnames));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->verifyToken = $this->auth->createToken();
        $passwordEncrypt = hash('sha256', $this->password);

        $sqlUsers = "INSERT INTO " . $this->tableUsers . " (uid, usuario, correo, clave, token_verificacion, verificado) ";
        $sqlUsers .= "VALUES (" . $this->uid . ", '" . $this->user . "', '" . $this->email . "', '" . $passwordEncrypt . "', '" . $this->verifyToken . "', null)";

        $sqlDataUsers = "INSERT INTO " . $this->tabeDataUsers . " (uid, dni, nombres, apellidos, id_genero, telefono, direccion, perfil) ";
        $sqlDataUsers .= "VALUES (" . $this->uid . ", '" . $this->dni . "', '" . $this->names . "', '" . $this->surnames . "', '" . $this->genre . "', '" . $this->phone . "', '" . $this->address . "', '" . $this->profile . "')";

        if (mysqli_query($this->connection, $sqlUsers)) {
            if (mysqli_query($this->connection, $sqlDataUsers)) {
                $template = file_get_contents("../../templates/verify.php");
                $dir = "http://" . $_SERVER['HTTP_HOST'] . "/api/auth/verify.php?token=" . $this->verifyToken . "&uid=" . $this->uid;
                $this->auth->username = $this->user;
                $this->auth->email = $this->email;
                $var = array(
                    '{{Direccion}}' => $dir,
                    '{{Usuario}}' => $this->user,
                );

                foreach ($var as $key => $value) {
                    $template = str_replace($key, $value, $template);
                }
                return $this->auth->gmail($template, "Correo de verificación");
            } else return false;
        } else return false;
    }
}
