<?php

require_once(__DIR__ . "/./config.php");
require_once(__DIR__ . "/./database.php");
require_once(__DIR__ . "/./Product.php");

$config = load_config("config.ini");
$conn = connect($config);

Product::setConnection($conn);

