<?php

//Inicializar variables para mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

include_once '../config.php';

$baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $baseDir;

//Definiendo constante BASE_URL
define('BASE_URL', $baseUrl);

//var_dump($baseDir);
//var_dump($baseUrl);

$route = $_GET['route'] ?? '/';

function render($fileName, $params = []) {
    //Almacena internamente la salida del sistema en un buffer
    ob_start();
    extract($params);
    include $fileName;

    return ob_get_clean();
}

use Phroute\Phroute\RouteCollector;
$router = new RouteCollector();

$router->get('/admin', function() {
    return render('../views/admin/index.php');
});

$router->get('/admin/posts', function() use ($pdo) {

    $query = $pdo->prepare('SELECT * FROM blog_posts ORDER BY id DESC');
    $query->execute();
    $blogPosts = $query->fetchAll(PDO::FETCH_ASSOC);

    return render('../views/admin/posts.php', ['blogPosts' => $blogPosts]);
});

$router->get('/admin/posts/create', function() {
    return render('../views/admin/insert-post.php');
});

$router->post('/admin/posts/create', function() use ($pdo) {
    $sql = 'INSERT INTO blog_posts (title, content) VALUES (:title, :content)';
    $query = $pdo->prepare($sql);
    $result = $query->execute([
        'title' => $_POST['title'],
        'content' => $_POST['content']
    ]);

    return render('../views/admin/insert-post.php', ['result' => $result]);
});

$router->get('/admin/lotes', function() use ($pdo) {

    $query = $pdo->prepare('SELECT * FROM ctas_lotes ORDER BY id_lote DESC');
    $query->execute();
    $listaLotes = $query->fetchAll(PDO::FETCH_ASSOC);

    return render('../views/admin/lista-lotes.php', ['listaLotes' => $listaLotes]);
});

$router->get('/admin/lotes/crear', function() {
    return render('../views/admin/agregar-lote.php');
});

$router->post('/admin/lotes/crear', function() use ($pdo) {
    $sql = 'INSERT INTO ctas_lotes ( lote_anio, fecha_creacion, fecha_modificacion, comentario, id_user, num_oficio_ca, fecha_oficio_ca, num_ticket_mesa, fecha_atendido ) VALUES (:lote, NOW(), NOW(), :comentario, 2, "PENDIENTE", NULL, "PENDIENTE", NULL)';
    $query = $pdo->prepare($sql);
    $result = $query->execute([
        'lote' => $_POST['lote'],
        'comentario' => $_POST['comentario']
    ]);

    return render('../views/admin/agregar-lote.php', ['result' => $result]);
});

$router->get('/', function() use ($pdo) {

    $query = $pdo->prepare('SELECT * FROM blog_posts ORDER BY id DESC');
    $query->execute();

    $blogPosts = $query->fetchAll(PDO::FETCH_ASSOC);
    return render('../views/index.php', ['blogPosts' => $blogPosts]);

});

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $route);

echo $response;