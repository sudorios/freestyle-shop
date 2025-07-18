<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/sucursales_utils.php';
require_once __DIR__ . '/sucursales_queries.php';

verificarMetodoPost();

$nombre = trim($_POST['nombre_sucursal'] ?? '');
$direccion = trim($_POST['direccion_sucursal'] ?? '');
$tipo = $_POST['tipo_sucursal'] ?? '';
$id_supervisor = $_POST['id_supervisor'] ?? '';
$estado = 1;

$errores = validarCamposSucursal($nombre, $direccion, $tipo, $id_supervisor);
if (!empty($errores)) {
    $error_msg = implode(', ', $errores);
    header('Location: ../../sucursales.php?error=1&msg=' . urlencode($error_msg));
    exit();
}

$params = array($nombre, $direccion, $tipo, $estado, $id_supervisor);
$result = pg_query_params($conn, insertSucursalQuery(), $params);
manejarResultadoConsulta($result, $conn, '../../sucursales.php?success=2', '../../sucursales.php?error=1');
?> 