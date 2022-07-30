<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
try {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') { /* Comprobando si el método de solicitud es DELETE. */
        require "../../database.php";
        require "../../products.php";
        $database = new Database();
        $connection = $database->getConnection();
        $products = new Products($connection);
        $data = json_decode(file_get_contents("php://input"));

        /* Esto es verificar si los datos están configurados y si la identificación está configurada y
        si la longitud de la identificación es mayor que 0. */
        if (isset($data)) {
            if (isset($data->id) && strlen($data->id) > 0) {
                $products->pid = $data->id;

                /* Comprobando si el producto fue eliminado. */
                if ($products->delete()) {
                    $database->closeConnection();
                    echo json_encode(array([
                        'message' => 'Producto eliminado exitosamente.'
                    ]));
                } else {
                    $database->closeConnection();
                    echo json_encode(array([
                        'message' => 'A occurido un error, el producto no se elimino.'
                    ]));
                }
            } else {
                $database->closeConnection();
                echo json_encode(array([
                    'message' => 'El campo requerido no existe o el campo esta vacio, por favor revisar el contenido enviado corresponda con el requerido.'
                ]));
            }
        } else { /* Comprobando si los datos están en formato JSON. */
            $database->closeConnection();
            echo json_encode(array([
                'message' => 'Debe enviar los datos en formato JSON.'
            ]));
        }
    } else echo json_encode(array(['message' => 'Metodo de solicitud no valido.']));
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}
