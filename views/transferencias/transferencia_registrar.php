<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/transferencia_utils.php';
require_once __DIR__ . '/transferencia_queries.php';
require_once __DIR__ . '/../kardex/kardex_queries.php';

verificarMetodoPost();

$origen = $_POST['origen'] ?? '';
$destino = $_POST['destino'] ?? '';
$producto = $_POST['producto'] ?? '';
$cantidad = $_POST['cantidad'] ?? 1;
$fecha = $_POST['fecha'] ?? '';
$id_usuario = obtenerIdUsuarioSesion();

$errores = validarCamposTransferencia($origen, $destino, $producto, $cantidad, $fecha);
if (!empty($errores)) {
    $error_msg = implode(', ', $errores);
    header('Location: ../../transferencia.php?error=1&msg=' . urlencode($error_msg));
    exit();
}

if (!validarFecha($fecha)) {
    header('Location: ../../transferencia.php?error=1&msg=' . urlencode('No se puede registrar una transferencia con fecha futura'));
    exit();
}

$fecha = formatearFecha($fecha);

$params = array(
    $origen,
    $destino,
    $producto,
    $cantidad,
    $fecha,
    $id_usuario
);

$sql_insertar_transferencia = getInsertarTransferenciaQuery();
$result = pg_query_params($conn, $sql_insertar_transferencia, $params);

if (!$result) {
    $error_msg = pg_last_error($conn);
    header('Location: ../../transferencia.php?error=1&msg=' . urlencode('Error al insertar: ' . $error_msg));
    exit();
}

$sql_check_origen = getCantidadInventarioSucursalQuery();
$res_check_origen = pg_query_params($conn, $sql_check_origen, array($producto, $origen));
if ($row = pg_fetch_assoc($res_check_origen)) {
    $nueva_cantidad = $row['cantidad'] - $cantidad;
    $sql_update_origen = getActualizarInventarioSucursalQuery();
    pg_query_params($conn, $sql_update_origen, array($nueva_cantidad, $producto, $origen));
}

$sql_check_destino = getCantidadInventarioSucursalQuery();
$res_check_destino = pg_query_params($conn, $sql_check_destino, array($producto, $destino));
if ($row = pg_fetch_assoc($res_check_destino)) {
    $nueva_cantidad = $row['cantidad'] + $cantidad;
    $sql_update_destino = getActualizarInventarioSucursalQuery();
    pg_query_params($conn, $sql_update_destino, array($nueva_cantidad, $producto, $destino));
} else {
    $sql_insert_destino = getInsertarInventarioSucursalQuery();
    pg_query_params($conn, $sql_insert_destino, array($producto, $destino, $cantidad));
}

$sql_insertar_kardex = InsertarKardexQuery();

$params_kardex_salida = array($producto, $cantidad, 'SALIDA', 0, $fecha, $id_usuario, $origen);
$params_kardex_ingreso = array($producto, $cantidad, 'INGRESO', 0, $fecha, $id_usuario, $destino);
pg_query_params($conn, $sql_insertar_kardex, $params_kardex_salida);
pg_query_params($conn, $sql_insertar_kardex, $params_kardex_ingreso);

manejarResultadoConsulta($result, $conn, '../../transferencia.php?success=2', '../../transferencia.php?error=1');
?> 