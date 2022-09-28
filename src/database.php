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
    create_table_if_not_exists($conn);
    return $conn;
}

function create_table_if_not_exists($connection){
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS products(
    code         VARCHAR(30)                NOT NULL,
    barcode      VARCHAR(60),
    status       ENUM('draft', 'imported'),
    imported_t   DATETIME,
    url          VARCHAR(250),
    product_name VARCHAR(400),
    quantity     VARCHAR(60),
    categories   VARCHAR(400),
    packaging    VARCHAR(400),
    brands       VARCHAR(60),
    image_url    VARCHAR(250),
    UNIQUE(code)
);
SQL;

    $connection->exec($sql);
}

