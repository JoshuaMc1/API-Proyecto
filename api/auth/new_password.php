<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Content-Type: application/json; charset=UTF-8");
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

        /* Comprobando si los datos están configurados y luego está configurando el correo electrónico,
        tokenReset y newPassword. Luego está llamando a la función resetPassword. Si la función
        resetPassword devuelve verdadero, entonces está haciendo eco de un mensaje. Si la función
        resetPassword devuelve falso, entonces está repitiendo un mensaje diferente. */
        if (isset($data)) {
            $auth->email = $data->email;
            $auth->tokenReset = $data->resetCode;
            $auth->newPassword = $data->newPassword;
            if ($auth->resetPassword()) {
                echo json_encode(array([
                    'message' => 'Contraseña restablecida exitosamente.',
                ]));
            } else echo json_encode(array(['message' => 'A ocurrido un error al cambiar la contraseña.']));
        } else { /* Comprobando si los datos están en formato JSON. */
            $database->closeConnection();
            echo json_encode(array(['message' => 'Debe enviar los datos en formato JSON.']));
        }
    } else echo json_encode(array(['message' => 'Metodo de solicitud no valido.']));
} catch (\Exception $e) {
    echo json_encode(array(["message: " => $ex->getMessage()]));
}
