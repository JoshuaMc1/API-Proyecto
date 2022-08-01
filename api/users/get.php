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
    $data = $users->getInfoUsers();
    $countData = mysqli_num_rows($data);
    $response = array();

    if ($countData > 0) { /* Comprobando si hay productos en la base de datos. */
        while ($row = mysqli_fetch_assoc($data)) { /* Un bucle que va a iterar sobre los datos que provienen de la base de datos. */
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
            array_push($response, $newResponse);
        }
        $database->closeConnection();
        echo json_encode($response); /* Devolver los datos al cliente. */
    } else { /* Comprobando si no hay productos en la base de datos. */
        $database->closeConnection();
        $response = array(
            'message: ' => 'No hay usuarios.'
        );
        echo json_encode($response);
    }
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}