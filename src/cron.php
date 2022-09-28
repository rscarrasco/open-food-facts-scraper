<?php

include_once(__DIR__ . "/config.php");
include_once(__DIR__ . "/database.php");
include_once(__DIR__ . "/Product.php");
include_once(__DIR__ . "/OpenFoodFactsScraper.php");

$config = load_config("config.ini");
$conn = connect($config);
Product::setConnection($conn);

$scraper = new OpenFoodFactsScraper();
$scraper->scrape();
?>

