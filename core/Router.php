<?php
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

function isCarritoApi() {
    $ctrl = $_GET['controller'] ?? '';
    $act = $_GET['action'] ?? '';
    $apiActions = ['registrar', 'actualizar', 'eliminar', 'datos', 'contador'];
    return $ctrl === 'carrito' && in_array($act, $apiActions);
}

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controllerObj = new $controllerName();
        if (method_exists($controllerObj, $action)) {
            $controllerObj->$action();
        } else {
            if (isCarritoApi()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Acción no encontrada.']);
                exit;
            }
            die('Acción no encontrada.');
        }
    } else {
        if (isCarritoApi()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Controlador no encontrado.']);
            exit;
        }
        die('Controlador no encontrado.');
    }
} else {
    if (isCarritoApi()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Archivo de controlador no encontrado.']);
        exit;
    }
    die('Archivo de controlador no encontrado.');
} 