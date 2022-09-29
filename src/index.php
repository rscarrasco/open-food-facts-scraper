<?php

require_once("./boot.php");

function getCode($path){
    $pattern = '#^\/products\/(.*)#';
    preg_match($pattern, $path, $matches);
    return $matches[1];
}

function getQueryStringParameters($uri){
    $querystring = explode('?', $uri)[1] ?? '';
    $parameters = explode('&', $querystring);
    $result = array();
    foreach($parameters as $parameter){
        $splitted_parameter = explode('=', $parameter);
        $key = $splitted_parameter[0];
        $value = $splitted_parameter[1];
        $result[$key] = $value;
    }
    return $result;
}

$path = $_SERVER['PATH_INFO'] ?? '/';
$request_uri = $_SERVER['REQUEST_URI'];
$_GET['code'] = getCode($path);
$_GET = array_merge(getQueryStringParameters($request_uri), $_GET);

$endpoint = $_GET['code'] ? '/products' : $path;

switch($endpoint){
    case '/'        : require('./api/get-root.php'); 
                      break;

    case '/products': $_GET['code'] ? require('./api/get-single-product.php') : require('./api/get-all-products.php');
                      break;

    case '/cron'    : require('./cron.php');
                      break;

    default         : http_response_code(404) ;
}

