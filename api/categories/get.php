<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    require "../../database.php";
    require "../../categories.php";
    $database = new Database();
    $connection = $database->getConnection();
    $categories = new Categories($connection);
    $data = $categories->getCategories();
    $countData = mysqli_num_rows($data);
    $response = array();

    /* Esto es verificar si los datos son mayores que 0, si lo son, recorrerá los datos y los agregará
    a la matriz de respuesta. Si no es así, cerrará la conexión y mostrará un mensaje diciendo que
    no hay registros. */
    if ($countData > 0) {
        while ($row = mysqli_fetch_assoc($data)){
            $newResponse = array(
                'id' => intval($row['id']),
                'categoria' => $row['categoria']
            );
            array_push($response, $newResponse);
        }
        $database->closeConnection();
        echo json_encode($response);
    } else {
        $database->closeConnection();
        echo json_encode(array([
            "message" => "No hay registros",
        ]));
    }
} catch (Exception $ex) {
    echo json_encode(array(["Error: " => $ex->getMessage()]));
}