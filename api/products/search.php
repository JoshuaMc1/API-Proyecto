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
    if (isset($_GET['id'])) {
        if (strlen($_GET['id']) > 0) {
            $products->id = $_GET['id'];
            $data = $products->getSingleProduct();
            $countData = $data->num_rows;
            $responce = array();
            if ($countData > 0) {
                while ($row = $data->fetch_assoc()) {
                    $responce[] = $row;
                }
                $database->closeConnection();
                http_response_code(200);
                echo json_encode($responce, JSON_UNESCAPED_UNICODE);
            } else {
                $database->closeConnection();
                $responce = array([
                    'Error code:' => '204',
                    'Message: ' => 'No query content'
                ]);
                echo json_encode($responce, JSON_UNESCAPED_UNICODE);
            }
        }else {
            $database->closeConnection();
            $responce = array([
                'Error code:' => '404',
                'Message: ' => 'There are one or more required fields that are empty.'
            ]);
            http_response_code(404);
            echo json_encode($responce, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $database->closeConnection();
        $responce = array([
            'Error code:' => '404',
            'Message: ' => 'The search key is required.'
        ]);
        http_response_code(404);
        echo json_encode($responce, JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}
