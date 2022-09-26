<?php

function connect($config){
    $host = $config['database']['host'];
    $user = $config['database']['user'];
    $pass = $config['database']['pass'];
    $db   = $config['database']['db'];
    $uri  = "mysql:host=$host;dbname=$db";
    try {
        $conn = new PDO($uri, $user, $pass);
    } catch(PDOException $e){
        echo $e->getMessage();
        die();
    }

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

