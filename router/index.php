<?php
require_once '../App/views/login.php'; // Load dependencies and bootstrap

$uri = trim($_SERVER['REQUEST_URI'], '/'); // Get the request URI
$routes = [
    'posts' => 'PostController@index',
    'posts/show' => 'PostController@show',
];

if (array_key_exists($uri, $routes)) {
    [$controller, $method] = explode('@', $routes[$uri]);
    $controller = "App\\Controllers\\$controller";
    $instance = new $controller();
    call_user_func([$instance, $method]);
} else {
    echo "404 Not Found";
}
?>
