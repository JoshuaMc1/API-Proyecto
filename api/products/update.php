<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") { /* Comprobando si el método de solicitud es POST. */
        require "../../database.php";
        require "../../products.php";
        $database = new Database();
        $connection = $database->getConnection();
        $products = new Products($connection);
        $data = json_decode(file_get_contents("php://input"));

        /* Esta es una validación de los datos enviados por el cliente. */
        if (isset($data)) {
            /* Está comprobando si los datos están configurados. */
            if (isset($data->nombre, $data->precio, $data->idCategoria, $data->cantidad, $data->id)) {
                /* Comprobando si los datos están vacíos. */
                if ($data->nombre != "" || $data->precio != "" || $data->idCategoria != "" || $data->cantidad != "" || $data->id != "") {
                    $products->pid = $data->id;
                    $products->nombre = $data->nombre;
                    $products->descripcion = strlen($data->descripcion) > 0 ? $data->descripcion : null;
                    $products->precio = $data->precio;
                    $products->categoria = $data->idCategoria;
                    $products->cantidad = $data->cantidad;
                    $products->imagen_producto = strlen($data->imagen) > 0 ? $data->imagen : null;

                    if ($products->update()) {
                        $database->closeConnection();
                        echo json_encode(array(
                            'message' => 'Producto actualizado exitosamente.'
                        ));
                    } else {
                        $database->closeConnection();
                        echo json_encode(array(
                            'message' => 'A ocurrido un error al actualizar el producto.'
                        ));
                    }
                } else {
                    $database->closeConnection();
                    echo json_encode(array(
                        'message' => 'Campos requeridos estan vacios, por favor revisar el contenido enviado corresponda con el requerido.'
                    ));
                }
            } else {
                $database->closeConnection();
                echo json_encode(array(
                    'message' => 'Campos requeridos no existen, por favor revisar el contenido enviado corresponda con el requerido.'
                ));
            }
        } else { /* Comprobando si los datos están en formato JSON. */
            $database->closeConnection();
            echo json_encode(array('message' => 'Debe enviar los datos en formato JSON.'));
        }
    } else echo json_encode(array('message' => 'Metodo de solicitud no valido.'));
} catch (Exception $ex) {
    echo json_encode(array("Error: " => $ex->getMessage()));
}
