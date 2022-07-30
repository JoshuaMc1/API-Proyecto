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
    $data = $products->getProducts();
    $countData = mysqli_num_rows($data);
    $response = array();

    if ($countData > 0) { /* Comprobando si hay productos en la base de datos. */
        while ($row = mysqli_fetch_assoc($data)) { /* Un bucle que va a iterar sobre los datos que provienen de la base de datos. */
            $img = base64_encode($row['imagen_producto']);
            $newResponse = array(
                'pid' => intval($row['pid']),
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'idCategoria' => intval($row['id_categoria']),
                'categoria' => $row['categoria'],
                'precio' => floatval($row['precio']),
                'imagen_producto' => $img,
                'cantidad' => intval($row['cantidad']),
            );
            array_push($response, $newResponse);
        }
        $database->closeConnection();
        echo json_encode($response); /* Devolver los datos al cliente. */
    } else { /* Comprobando si no hay productos en la base de datos. */
        $database->closeConnection();
        $response = array([
            'message: ' => 'No hay productos.'
        ]);
        echo json_encode($response);
    }
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}
