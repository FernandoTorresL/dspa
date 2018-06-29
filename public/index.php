<?php

//Inicializar variables para mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//config.php, antes connectBD.php
include_once '../config.php';

$route = $_GET['route'] ?? '/';

//Prueba de route:
switch ($route) {
    case '/':
        require '../index.php';
        break;
    case '/admin':
        require '../admin/index.php';
        break;
}