<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require "../../database.php";
require "../../authentication.php";

$database = new Database();
$connection = $database->getConnection();
$item = new Authentication($connection);

if (isset($_GET['user'], $_GET['password'])) {
    $item->user = $_GET['user'];
    $item->password = $_GET['password'];
    $item->auth();

    if ($item->user != null) {
        $dataUser = array(
            "authentication" => "true",
        );

        http_response_code(200);
        echo json_encode($dataUser);
    } else {
        http_response_code(404);
        echo json_encode("Employee not found.");
    }
}else{
    http_response_code(404);
    echo json_encode("Error");
}