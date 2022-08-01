<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    require "../../database.php";
    require "../../users.php";
    $database = new Database();
    $connection = $database->getConnection();
    $users = new Users($connection);
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data)) {
        if ($data->uid != "") {
            $users->uid = $data->uid;
            $response = $users->searchUser();
            $countData = mysqli_num_rows($response);
            $row = mysqli_fetch_assoc($response);
            if ($countData > 0) {
                $img = $row['perfil'] != null ? base64_encode($row['perfil']) : null;
                $newResponse = array(
                    'uid' => intval($row['uid']),
                    'usuario' => $row['usuario'],
                    'correo' => $row['correo'],
                    'rol' => $row['rol'],
                    'verificado' => $row['verificado'],
                    'dni' => $row['dni'],
                    'nombres' => $row['nombres'],
                    'apellidos' => $row['apellidos'],
                    'telefono' => $row['telefono'],
                    'direccion' => $row['direccion'],
                    'idGenero' => $row['id'],
                    'genero' => $row['genero'],
                    'perfil' => $img,
                );
                $database->closeConnection();
                echo json_encode($newResponse);
            } else {
                $database->closeConnection();
                echo json_encode(array("message" => "El usuario no existe."));
            }
        } else {
            $database->closeConnection();
            echo json_encode(array("message" => "Es necesario ingresar el codigo del usuario."));
        }
    } else { /* Comprobando si los datos están en formato JSON. */
        $database->closeConnection();
        echo json_encode(array('message' => 'Debe enviar los datos en formato JSON.'));
    }
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}
