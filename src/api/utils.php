<?php
require_once(__DIR__ . '/./http-status-codes.php');

function compose_error_response($status, $message, $path){
    global $http_status_code;
    $response = array();
    $response["timestamp"] = date('c');
    $response["status"] = $status;
    $response["error"] = $http_status_code[$status];
    $response["message"] = $message;
    $response["path"] = $path;
    return $response;
}

function respond($path, $response, $status = 200, $message = ""){ 
    http_response_code($status);
    header("Content-type: application/json");
    echo json_encode($response);
    die;
}

