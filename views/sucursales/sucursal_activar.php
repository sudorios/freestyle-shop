<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/sucursales_queries.php';

// if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
//     header('Location: ../../login.php');
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../sucursales.php?error=2');
    exit();
}

$id_sucursal = filter_input(INPUT_POST, 'id_sucursal');
if (!$id_sucursal || !is_numeric($id_sucursal)) {
    header('Location: ../../sucursales.php?error=3');
    exit();
}

$sql = updateEstadoSucursalQuery();
$params = [true, $id_sucursal];
$result = pg_query_params($conn, $sql, $params);

if ($result) {
    header('Location: ../../sucursales.php?success=4');
    exit();
} else {
    header('Location: ../../sucursales.php?error=5');
    exit();
} 