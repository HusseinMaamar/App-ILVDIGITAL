<?php


use router\Router;
require_once __DIR__ . '/Autoload.php';


$router = new Router;
$router->handleRequest();

?>