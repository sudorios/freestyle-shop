<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'transferencia_utils.php';
include_once 'transferencia_queries.php';
include_once '../kardex/kardex_queries.php';

verificarSesionAdmin();
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

global $sql_insertar_transferencia;
$result = pg_query_params($conn, $sql_insertar_transferencia, $params);

if (!$result) {
    $error_msg = pg_last_error($conn);
    header('Location: ../../transferencia.php?error=1&msg=' . urlencode('Error al insertar: ' . $error_msg));
    exit();
}

$sql_check_origen = "SELECT cantidad, precio_costo FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
$res_check_origen = pg_query_params($conn, $sql_check_origen, array($producto, $origen));
$precio_costo_origen = 0;
if ($row = pg_fetch_assoc($res_check_origen)) {
    $nueva_cantidad = $row['cantidad'] - $cantidad;
    $precio_costo_origen = $row['precio_costo'];
    $sql_update_origen = "UPDATE inventario_sucursal SET cantidad = $1 WHERE id_producto = $2 AND id_sucursal = $3";
    pg_query_params($conn, $sql_update_origen, array($nueva_cantidad, $producto, $origen));
}

$sql_check_destino = "SELECT cantidad, precio_costo FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
$res_check_destino = pg_query_params($conn, $sql_check_destino, array($producto, $destino));
$precio_costo_destino = $precio_costo_origen; // Por defecto, mismo costo
if ($row = pg_fetch_assoc($res_check_destino)) {
    $nueva_cantidad = $row['cantidad'] + $cantidad;
    $precio_costo_destino = $row['precio_costo'];
    $sql_update_destino = "UPDATE inventario_sucursal SET cantidad = $1 WHERE id_producto = $2 AND id_sucursal = $3";
    pg_query_params($conn, $sql_update_destino, array($nueva_cantidad, $producto, $destino));
} else {
    $sql_insert_destino = "INSERT INTO inventario_sucursal (id_producto, id_sucursal, cantidad) VALUES ($1, $2, $3)";
    pg_query_params($conn, $sql_insert_destino, array($producto, $destino, $cantidad));
}

global $sql_insertar_kardex;
$params_kardex_salida = array($producto, $cantidad, 'SALIDA', $precio_costo_origen, $fecha, $id_usuario);
$params_kardex_ingreso = array($producto, $cantidad, 'INGRESO', $precio_costo_destino, $fecha, $id_usuario);
pg_query_params($conn, $sql_insertar_kardex, $params_kardex_salida);
pg_query_params($conn, $sql_insertar_kardex, $params_kardex_ingreso);

manejarResultadoConsulta($result, $conn, '../../transferencia.php?success=2', '../../transferencia.php?error=1');
?> 