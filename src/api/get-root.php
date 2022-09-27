<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$json = <<<json
{
   "message": "Fullstack Challenge 20201026"
}
json;

echo $json;

