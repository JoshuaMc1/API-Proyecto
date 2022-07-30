<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
try {
    require "../../database.php";
    require "../../products.php";
    $database = new Database();
    $connection = $database->getConnection();
    $products = new Products($connection);
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data)) {
        if ($data->id != "") {
            $products->pid = $data->id;
            $response = $products->getSingleProduct();
            $countData = mysqli_num_rows($response);

            if ($countData > 0) {
                $row = mysqli_fetch_assoc($response);
                $img = base64_encode($row['imagen_producto']);
                $newResponse = array(
                    'pid' => $row['pid'],
                    'nombre' => $row['nombre'],
                    'descripcion' => $row['descripcion'],
                    'idCategoria' => $row['id_categoria'],
                    'categoria' => $row['categoria'],
                    'precio' => $row['precio'],
                    'imagen_producto' => $img,
                    'cantidad' => $row['cantidad'],
                );
                $database->closeConnection();
                echo json_encode([$newResponse]);
            } else {
                $database->closeConnection();
                echo json_encode(array(["message" => "El producto no existe"]));
            }
        } else {
            $database->closeConnection();
            echo json_encode(array(["message" => "Es necesario el codigo del producto."]));
        }
    } else { /* Comprobando si los datos están en formato JSON. */
        $database->closeConnection();
        echo json_encode(array(['message' => 'Debe enviar los datos en formato JSON.']));
    }
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}
