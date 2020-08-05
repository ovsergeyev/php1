<?php
require_once('../config/config.php');

$url_array = explode("/", $_SERVER['REQUEST_URI']);

if($url_array[1] == "")
    $page_name = "index";
else
    $page_name = $url_array[1];

echo renderPage($page_name);