<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    require "../../database.php";
    require "../../categories.php";
    $database = new Database();
    $connection = $database->getConnection();
    $categories = new Categories($connection);
    $data = $categories->getCategories();
    $countData = $data->num_rows;
    $responce = array();
    if($countData > 0){
        while ($row = $data->fetch_assoc()) {
            $responce[] = $row;
        }
        http_response_code(200);
        echo json_encode($responce, JSON_UNESCAPED_UNICODE);
        $database->closeConnection();
    } else {
        $database->closeConnection();
        $responce = array([
            'Error code:' => '204',
            'Message: ' => 'No query content'
        ]);
        echo json_encode($responce, JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $ex) {
    echo json_encode(["Error: " => $ex->getMessage()]);
}