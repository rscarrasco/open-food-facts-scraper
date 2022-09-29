<?php

header('Content-type: application/x-yaml');
echo file_get_contents(__DIR__ . "/../docs/api.yml");

?>
