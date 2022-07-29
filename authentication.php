<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'mailer/vendor/autoload.php';

class Authentication
{
    private $connection;
    private $tablaUsuarios = "usuarios";
    private $tablaRestablecerClave = "restablecer_clave";
    private $tablaTokenPersonal = "token_personal";
    public $email;
    public $uid;
    public $username;
    public $password;
    public $newPassword;
    public $token;
    public $tokenReset;
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

            if ($this->verifyPersonalToken()) $this->updateToken($ntoken);
            else $this->insetToken($ntoken);

            return array([
                'authenticated' => true,
                'uid' => $dataUser['uid'],
                'token' => $ntoken,
            ]);
        } else return false;
    }

    /**
     * Si el usuario existe, cree un nuevo token e insértelo en la base de datos.
     * 
     * @return Una matriz con la siguiente estructura:
     */
    public function login2()
    {
        $this->result = mysqli_query($this->connection, "SELECT * FROM " . $this->tablaTokenPersonal . " WHERE uid = " . $this->uid . " AND token = '" . $this->token . "'");
        if (mysqli_num_rows($this->result) > 0) {
            $token = $this->createToken();
            if ($this->insetToken($token)) {
                return array([
                    'authenticated' => true,
                    'uid' => $this->uid,
                    'token' => $token,
                ]);
            }
        } else return false;
    }

    /**
     * Comprueba si el correo electrónico y el token están en la base de datos, si lo están, actualiza
     * la contraseña y envía un correo electrónico al usuario.
     * 
     * @return un valor booleano.
     */
    public function resetPassword()
    {
        $template = file_get_contents("../../templates/success.php");
        if (mysqli_query($this->connection, "SELECT * FROM " . $this->tablaRestablecerClave . " WHERE correo = '" . $this->email . "' AND token = '" . $this->tokenReset . "' AND status = 0")) {
            if ($this->updatePassword()) {
                $sql = "UPDATE " . $this->tablaRestablecerClave . " SET status = 1 WHERE token = " . $this->tokenReset . " AND correo = '" . $this->email . "'";
                $this->result = mysqli_query($this->connection, $sql);
                return $this->gmail($template, "Contraseña actualizada");
            }
            return false;
        }
        return false;
    }

    /**
     * Toma el correo electrónico y la nueva contraseña del usuario, verifica si el correo electrónico
     * existe en la base de datos, si existe, toma el uid de la base de datos y actualiza la contraseña
     * con la nueva.
     * 
     * @return El resultado de la consulta.
     */
    public function updatePassword()
    {
        $sql = "SELECT * FROM " . $this->tablaUsuarios . " WHERE correo = '" . $this->email . "'";
        $result = mysqli_query($this->connection, $sql);
        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            $this->uid = $data['uid'];
            $passwordEncrypt = hash('sha256', $this->newPassword);
            $sqlUpdate = "UPDATE " . $this->tablaUsuarios . " SET clave = '" . $passwordEncrypt . "' WHERE uid = " . $this->uid;
            return mysqli_query($this->connection, $sqlUpdate);
        } else return false;
    }

    /**
     * Extrae los datos de la base de datos y devuelve el resultado de la función sendEmail()
     * 
     * @return el resultado de la función sendEmail().
     */
    public function extractDataUser()
    {
        $sql = "SELECT * FROM " . $this->tablaUsuarios . " WHERE correo = '" . $this->email . "'";
        $this->result = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($this->result) > 0) {
            $data = mysqli_fetch_assoc($this->result);
            $this->username = $data['usuario'];
            $this->uid = $data['uid'];
            return $this->sendEmail();
        } else return false;
    }

    /**
     * Envía un correo electrónico al usuario con un enlace para restablecer su contraseña
     * 
     * @return el resultado de la función de gmail.
     */
    public function sendEmail()
    {
        $token = $this->createNumericToken();
        if ($this->insertTokenResetPassword($token)) {
            $template = file_get_contents("../../templates/email.php");
            $var = array(
                '{{Usuario}}' => $this->username,
                '{{Correo}}' => $this->email,
                '{{Codigo}}' => $token,
            );

            foreach ($var as $key => $value) {
                $template = str_replace($key, $value, $template);
            }

            return $this->gmail($template, "Restablecer contraseña");
        }
        return false;
    }

    /**
     * Envía un correo electrónico al usuario.
     * 
     * @param template La plantilla HTML que desea enviar.
     * @param title El título del correo electrónico
     * 
     * @return El mensaje de error.
     */
    public function gmail($template, $title)
    {
        $mail = new PHPMailer();
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth   = true;
            $mail->Username   = 'macrinyoshida@gmail.com';
            $mail->Password   = 'ytfzkttxjbqcefvv';
            $mail->setFrom('macrinyoshida@gmail.com', 'Supermercado el econmico');
            $mail->addAddress($this->email, $this->username);
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->CharSet = 'utf-8';
            $mail->Body    = $template;
            $mail->setLanguage('es', '/optional/path/to/language/');
            if (!$mail->send()) {
                return false;
            } else return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Inserta un token en la base de datos.
     * 
     * @param token el token generado por la función generateToken()
     * 
     * @return El resultado de la consulta.
     */
    public function insertTokenResetPassword($token)
    {
        return mysqli_query($this->connection, "INSERT INTO " . $this->tablaRestablecerClave . " (correo, token) VALUES('" . $this->email . "', '" . $token . "')");
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
        if (mysqli_num_rows(mysqli_query($this->connection, "SELECT COUNT(*) FROM " . $this->tablaTokenPersonal)) > 0) return mysqli_num_rows(mysqli_query($this->connection, "SELECT * FROM " . $this->tablaTokenPersonal . " WHERE uid = " . $this->uid)) > 0 ? 1 : 0;
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
        return mysqli_query($this->connection, "UPDATE " . $this->tablaTokenPersonal . " SET token = '" . $token . "' WHERE uid = " . $this->uid);
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

    /**
     * Crea una cadena aleatoria de 8 números.
     * 
     * @return Una cadena de 8 números aleatorios.
     */
    public function createNumericToken()
    {
        $nums = '0123456789';
        $numsLength = strlen($nums);
        $randomNums = '';
        for ($i = 0; $i < 8; $i++) {
            $randomNums .= $nums[rand(0, $numsLength - 1)];
        }
        return $randomNums;
    }
}
