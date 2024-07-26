<?php
session_start();

require '../vendor/autoload.php';

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

$dispatcher = FastRoute\simpleDispatcher(require '../Application/core/routes.php');

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

ob_start(); // Inicia o buffer de saída

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        list($controller, $method) = explode('@', $handler);
        $controller = "Application\\Controllers\\$controller";

        if (class_exists($controller) && method_exists($controller, $method)) {
            call_user_func_array([new $controller, $method], $vars);
        } else {
            echo '404 Not Found';
        }
        break;
}

$output = ob_get_clean(); // Captura o buffer de saída

// Verifica se a resposta é JSON
if (strpos($output, '{"status":') === 0) {
    header('Content-Type: application/json');
    echo $output;
} else {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <title>Simple Framework</title>
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    </head>

    <body>
    <?php echo $output; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    </body>

    </html>
    <?php
}
?>
