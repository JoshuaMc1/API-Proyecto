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
    if (isset($_POST['category'], $_POST['id'])) {
        if (strlen($_POST['category']) > 0 || strlen($_POST['id'])  > 0) {
            $categories->categoria = $_POST['category'];
            $categories->id = $_POST['id'];
            if ($categories->update()) {
                $responce = array([
                    'Code: ' => '201',
                    'Message: ' => 'Category upgraded successfully.'
                ]);
                $connection->close();
                http_response_code(201);
                echo json_encode($responce, JSON_UNESCAPED_UNICODE);
            } else {
                $responce = array([
                    'Code: ' => '404',
                    'Message: ' => 'An error has occurred, the category has not been updated.'
                ]);
                $connection->close();
                http_response_code(404);
                echo json_encode($responce, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $connection->close();
            $responce = array([
                'Error code:' => '404',
                'Message: ' => 'There are one or more required fields that are empty.'
            ]);
            http_response_code(404);
            echo json_encode($responce, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $connection->close();
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
