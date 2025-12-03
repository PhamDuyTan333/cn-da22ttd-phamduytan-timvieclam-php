<?php

// Start session
session_start();

require_once 'config/config.php';
require_once 'app/core/helpers.php';

// Set security headers
set_security_headers();

$request = isset($_GET['url']) ? $_GET['url'] : '';
$request = rtrim($request, '/');

// Remove query string if exists
$request = strtok($request, '?');

$request = filter_var($request, FILTER_SANITIZE_URL);
$request = explode('/', $request);

$controllerName = !empty($request[0]) ? ucfirst($request[0]) . 'Controller' : 'HomeController';
$action = isset($request[1]) ? $request[1] : 'index';
$params = array_slice($request, 2);

$controllerPath = BASE_PATH . 'app/controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        } else {
            http_response_code(404);
            require_once BASE_PATH . 'app/views/errors/404.php';
        }
    } else {
        http_response_code(404);
        require_once BASE_PATH . 'app/views/errors/404.php';
    }
} else {
    http_response_code(404);
    require_once BASE_PATH . 'app/views/errors/404.php';
}
?>
