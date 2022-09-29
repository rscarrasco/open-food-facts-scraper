<?php
require_once(__DIR__ . '/../boot.php');
require_once(__DIR__ . '/utils.php');

$start    = $_GET['start'] ?? 0;
$limit    = $_GET['limit'] ?? 10;
$path     = "/products";
$response = array();

if($start < 0){
    $status = 400;
    $message = 'Start parameter cannot be lower than 0';
    $response = compose_error_response($status, $message, $path);
    respond($path, $response, $status, $message);
}

if ($limit <= 0){
    $status = 400;
    $message = 'Limit parameter cannot be lower or equal than 0';
    $response = compose_error_response($status, $message, $path);
    respond($path, $response, $status, $message);
}

$products = Product::fetchAll($start, $limit);
$data = array();
$data['data'] =  array();
foreach($products as $product){
    $data['data'][] = $product->getData();
}
$data['links'] = array();

$fetched_itens = count($data['data']);

if($start >= $limit){
    $prevstart = $start - $limit;
    $data['links']['prev'] = "$path?start=$prevstart&limit=$limit";
}
$data['links']['current'] = "$path?start=$start&limit=$limit";
if($fetched_itens == $limit){
    $nextstart = $start + $limit;
    $data['links']['next'] = "$path?start=$nextstart&limit=$limit";
}
respond($path, $data);

?>

