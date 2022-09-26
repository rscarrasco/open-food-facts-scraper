<?php

function load_config($filepath){
   return parse_ini_file($filepath, true);
}

