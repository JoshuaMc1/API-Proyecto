<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
try {
    require "../../database.php";
    require "../../products.php";
    $database = new Database();
    $connection = $database->getConnection();
    $products = new Products($connection);
    $responce = array();
    if (isset(
        $_POST['id'],
        $_POST['name'],
        $_POST['description'],
        $_POST['unit_price'],
        $_POST['quantity'],
        $_POST['id_category'])) {
        if (strlen($_POST['id']) > 0 || strlen($_POST['name'] > 0 || strlen($_POST['unit_price'] > 0 ||
            strlen($_POST['quantity'] || strlen($_POST['id_category']) > 0)))) {
            $products->id = $_POST['id'];
            $products->nombre = $_POST['name'];
            $products->descripcion = $_POST['description'];
            $products->precio_unitario = $_POST['unit_price'];
            $products->categoria = $_POST['id_category'];
            $products->cantidad = $_POST['quantity'];
            if (isset($_POST['media'])) {
                $products->imagen = $_POST['media'];
            } else $products->imagen = null;
            if ($products->update()) {
                $responce = array([
                    'Code: ' => '201',
                    'Message: ' => 'Product upgraded successfully.'
                ]);
                http_response_code(201);
                echo json_encode($responce, JSON_UNESCAPED_UNICODE);
            } else {
                $responce = array([
                    'Code: ' => '404',
                    'Message: ' => 'An error has occurred, the product has not been updated.'
                ]);
                http_response_code(404);
                echo json_encode($responce, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $responce = array([
                'Error code:' => '404',
                'Message: ' => 'There are one or more required fields that are empty.'
            ]);
            http_response_code(404);
            echo json_encode($responce, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $responce = array([
            'Error code:' => '404',
            'Message: ' => 'An error has occurred, the mandatory fields are required.'
        ]);
        http_response_code(404);
        echo json_encode($responce, JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}
