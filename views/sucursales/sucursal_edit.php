<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/sucursales_queries.php';
require_once __DIR__ . '/sucursales_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$id_sucursal = filter_input(INPUT_POST, 'id_sucursal');

verificarIdSucursal($id_sucursal);

$sql_check = getSucursalByIdQuery();
$result_check = pg_query_params($conn, $sql_check, [$id_sucursal]);

verificarResultadoConsulta($result_check, '../../sucursales.php', 3);

$nombre_sucursal = trim(filter_input(INPUT_POST, 'nombre_sucursal') ?? '');
$direccion_sucursal = trim(filter_input(INPUT_POST, 'direccion_sucursal') ?? '');
$tipo_sucursal = filter_input(INPUT_POST, 'tipo_sucursal');
$id_supervisor = filter_input(INPUT_POST, 'id_supervisor');
$estado_sucursal = true; // Siempre activo al editar

$errores = validarCamposSucursal($nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor);
if (!empty($errores)) {
    $error_msg = implode(', ', $errores);
    header('Location: ../../sucursales.php?error=1&msg=' . urlencode($error_msg));
    exit();
}

if (function_exists('verificarExistenciaSucursal') && verificarExistenciaSucursal($conn, $nombre_sucursal, $id_sucursal)) {
    header('Location: ../../sucursales.php?error=1&msg=' . urlencode('Ya existe una sucursal con ese nombre'));
    exit();
}

$sql = updateSucursalQuery();
$params = [$nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $estado_sucursal, $id_supervisor, $id_sucursal];
$result = pg_query_params($conn, $sql, $params);

manejarResultadoConsulta(
    $result,
    $conn,
    '../../sucursales.php?success=2',
    '../../sucursales.php?error=1'
); 