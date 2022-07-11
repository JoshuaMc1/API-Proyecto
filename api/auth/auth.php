<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
try {
    require "../../database.php";
    require "../../authentication.php";
    $database = new Database();
    $connection = $database->getConnection();
    $auth = new Authentication($connection);
    $responce = array();
    if (isset($_POST['user'], $_POST['password'])) {
        if (strlen($_POST['user']) > 0 || strlen($_POST['password']) > 0) {
            $auth->user = $_POST['user'];
            $auth->password = $_POST['password'];
            if ($auth->auth()) {
                $token = substr(md5($auth->user.time().uniqid()),0, 36).mt_rand(5, 36);
                $auth->updateToken($token);
                $responce = array([
                    'Code:' => '200',
                    'Authenticated:' => true,
                    'Token:' => $token
                ]);
                $connection->close();
                http_response_code(200);
                echo json_encode($responce, JSON_UNESCAPED_UNICODE);
            } else {
                $responce = array([
                    'Code:' => '404',
                    'Authenticated:' => false
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