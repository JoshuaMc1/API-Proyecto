<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") { /* Comprobando si el método de solicitud es POST. */
        require "../../database.php";
        require "../../categories.php";
        $database = new Database();
        $connection = $database->getConnection();
        $categories = new Categories($connection);
        $data = json_decode(file_get_contents("php://input"));

        /* Comprobando si los datos están configurados y luego está asignando los datos a la variable
        ías->categoría. */
        if (isset($data)) {
            $categories->categoria = $data->categoria;
            if ($categories->create()) {
                $database->closeConnection();
                echo json_encode(array([
                    'message' => 'Categoria creada exitosamente.',
                ]));
            } else {
                $database->closeConnection();
                echo json_encode(array([
                    'message' => 'A ocurrido un error al crear la categoria.'
                ]));
            }
        } else { /* Comprobando si los datos están en formato JSON. */
            $database->closeConnection();
            echo json_encode(array(['message' => 'Debe enviar los datos en formato JSON.']));
        }
    } else echo json_encode(array(['message' => 'Metodo de solicitud no valido.']));
} catch (Exception $ex) {
    echo json_encode(array(["Error: " => $ex->getMessage()]));
}
