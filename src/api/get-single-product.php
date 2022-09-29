<?php

require_once(__DIR__ . '/../boot.php');
require_once(__DIR__ . "/./utils.php");
require_once(__DIR__ . "/../Product.php");

$code = $_GET['code'];
$path = '/products/' . $code;

if(!$code){
    $status = 400;
    $message = 'Missing product code.';
    $response = compose_error_response($status, $message, $path);
    respond($path, $response, $status, $message);
}

$product = Product::fetchSingle($code);
$data = $product->getData();
respond($path, $data);

?>

