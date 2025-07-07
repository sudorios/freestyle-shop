<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/ingreso_queries.php';
require_once __DIR__ . '/ingreso_utils.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../ingreso.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../ingreso.php');
    exit();
}

$id_ingreso = trim(filter_input(INPUT_POST, 'id_ingreso'));
$fecha_ingreso = trim(filter_input(INPUT_POST, 'fecha_ingreso'));
$cantidad = trim(filter_input(INPUT_POST, 'cantidad_ingreso'));
$precio_costo_igv = trim(filter_input(INPUT_POST, 'precio_costo_igv'));
$precio_venta = trim(filter_input(INPUT_POST, 'precio_venta'));

$errores = [];
if (!$id_ingreso) $errores[] = 'ID de ingreso inválido';
if (!$fecha_ingreso) $errores[] = 'Fecha requerida';
if (!$cantidad || $cantidad <= 0) $errores[] = 'Cantidad inválida';
if ($precio_costo_igv === '' || !is_numeric($precio_costo_igv)) $errores[] = 'Precio costo IGV inválido';
if ($precio_venta === '' || !is_numeric($precio_venta)) $errores[] = 'Precio venta inválido';

if (!empty($errores)) {
    $msg = urlencode(implode(', ', $errores));
    header('Location: ../../ingreso.php?error=2&msg=' . $msg);
    exit();
}

$sql = updateIngresos();
$params = [$fecha_ingreso, $cantidad, $precio_costo_igv, $precio_venta, $id_ingreso];
$result = pg_query_params($conn, $sql, $params);

if ($result) {
    header('Location: ../../ingreso.php?success=2');
    exit();
} else {
    header('Location: ../../ingreso.php?error=1');
    exit();
} 