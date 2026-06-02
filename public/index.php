<?php

declare(strict_types = 1);

if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
    // Should only happen if someone runs the script via CLI
    exit;
}

require_once "../src/Router.php";

if (array_key_exists('PATH_INFO', $_SERVER)) {
    $pathInfo = $_SERVER['PATH_INFO'];
}
else {
    $pathInfo = '/';
}

$router = new Router();
$response = $router->route($_SERVER['REQUEST_METHOD'], $pathInfo);
$response->render();

?>