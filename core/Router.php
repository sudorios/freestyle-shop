<?php
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controllerObj = new $controllerName();
        if (method_exists($controllerObj, $action)) {
            $controllerObj->$action();
        } else {
            die('Acción no encontrada.');
        }
    } else {
        die('Controlador no encontrado.');
    }
} else {
    die('Archivo de controlador no encontrado.');
} 