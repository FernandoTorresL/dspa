<?php

//Inicializar variables para mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

//config.php, antes connectBD.php
include_once '../config.php';

$route = $_GET['route'] ?? '/';

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

$router->get('/', function () use ($pdo) {
    $query = $pdo->prepare('SELECT * FROM ctas_lotes ORDER BY id_lote DESC');
    $query->execute();

    $lotes = $query->fetchAll(PDO::FETCH_ASSOC);
    include '../views/index.php';
});

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $route);

echo $response;