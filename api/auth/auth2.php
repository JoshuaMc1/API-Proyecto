<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { /* Comprobando si el método de solicitud es POST. */
        require "../../database.php";
        require "../../authentication.php";
        $database   = new Database();
        $connection = $database->getConnection();
        $auth       = new Authentication($connection);
        $data       = json_decode(file_get_contents("php://input"));

        /* Esto es verificar si los datos están configurados, si es así, asignará el correo electrónico
        y la contraseña a la propiedad de correo electrónico y contraseña del objeto. Luego llamará
        a la función de inicio de sesión en la clase de autenticación. Si el resultado no es falso,
        cerrará la conexión e imprimira el resultado. Si el resultado es falso, cerrará la conexión y
        mostrará un mensaje diciendo que el correo electrónico o la contraseña son incorrectos. */
        if (isset($data)) {
            $auth->uid   = $data->uid;
            $auth->token = $data->token;
            $result      = $auth->login2();
            if ($result != false) {
                $database->closeConnection();
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            } else {
                $database->closeConnection();
                echo json_encode(array('message' => 'Al parecer a iniciado sesión desde otro dispositivo, si no lo ha hecho le recomendamos cambiar contraseña.'));
            }
        } else { /* Comprobando si los datos están en formato JSON. */
            $database->closeConnection();
            echo json_encode(array('message' => 'Debe enviar los datos en formato JSON.'));
        }
    } else echo json_encode(array('message' => 'Metodo de solicitud no valido.'));
} catch (Exception $ex) {
    echo json_encode(array("message: " => $ex->getMessage()));
}
