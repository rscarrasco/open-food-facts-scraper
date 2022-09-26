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
    code         VARCHAR(14)                NOT NULL,
    barcode      VARCHAR(30),
    status       ENUM('draft', 'imported'),
    imported_t   DATETIME,
    url          VARCHAR(120),
    product_name VARCHAR(200),
    quantity     VARCHAR(20),
    categories   VARCHAR(200),
    packaging    VARCHAR(50),
    brands       VARCHAR(30),
    image_url    VARCHAR(200),
    UNIQUE(code)
);
SQL;

    $connection->exec($sql);
}

