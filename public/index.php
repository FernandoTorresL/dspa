<?php

//Inicializar variables para mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

//config.php, antes connectBD.php
include_once '../config.php';

$baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $baseDir;

define('BASE_URL', $baseUrl);

//var_dump($baseDir);
//var_dump($baseUrl);

$route = $_GET['route'] ?? '/';

function render($fileName, $params = []) {
    //Almacena internamente la salida del sistema
    ob_start();
    extract($params);
    include $fileName;

    return ob_get_clean();
}

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

$router->get('/', function () use ($pdo) {
    $query = $pdo->prepare('SELECT * FROM ctas_lotes ORDER BY id_lote DESC');
    $query->execute();

    $lotes = $query->fetchAll(PDO::FETCH_ASSOC);
    return render('../views/index.php', ['lotes' => $lotes]);
});

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $route);

echo $response;