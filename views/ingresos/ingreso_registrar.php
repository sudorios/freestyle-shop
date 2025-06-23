<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'ingreso_utils.php';
include_once 'ingreso_queries.php';
include_once '../kardex/kardex_queries.php';

verificarSesionAdmin();
verificarMetodoPost();

$referencia = trim($_POST['ref'] ?? '');
$id_producto = $_POST['id_producto'] ?? '';
$precio_costo = $_POST['precio_costo'] ?? '';
$precio_costo_igv_paquete = $_POST['precio_costo_igv_paquete'] ?? ''; 
$precio_venta = $_POST['precio_venta'] ?? ''; 
$utilidad_esperada_total = $_POST['utilidad_esperada_total'] ?? '';
$utilidad_neta_total = $_POST['utilidad_neta_total'] ?? '';
$cantidad = $_POST['cantidad'] ?? 1;
$fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
$id_usuario = obtenerIdUsuarioSesion();
$id_sucursal = $_POST['id_sucursal'] ?? '';

$errores = validarCamposIngreso($referencia, $id_producto, $precio_costo, $precio_venta, $cantidad, $fecha_ingreso);

if (!empty($errores)) {
    $error_msg = implode(', ', $errores);
    header('Location: ../../ingreso.php?error=1&msg=' . urlencode($error_msg));
    exit();
}

if (!validarFecha($fecha_ingreso)) {
    header('Location: ../../ingreso.php?error=1&msg=' . urlencode('No se puede registrar un ingreso con fecha futura'));
    exit();
}

$fecha_ingreso = formatearFecha($fecha_ingreso);

$params = array(
    $referencia,
    $id_producto,
    $precio_costo,
    $precio_costo_igv_paquete,
    $precio_venta,
    $utilidad_esperada_total,
    $utilidad_neta_total,
    $cantidad,
    $fecha_ingreso,
    $id_usuario,
    $id_sucursal
);

$sql_insertar_ingreso = "INSERT INTO ingreso (ref, id_producto, precio_costo, precio_costo_igv, precio_venta, utilidad_esperada, utilidad_neta, cantidad, fecha_ingreso, id_usuario, id_sucursal) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";

$result = pg_query_params($conn, $sql_insertar_ingreso, $params);

if (!$result) {
    $error_msg = pg_last_error($conn);
    header('Location: ../../ingreso.php?error=1&msg=' . urlencode('Error al insertar: ' . $error_msg));
    exit();
}

// Actualizar o insertar en inventario
$sql_check_inventario = "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
$res_check = pg_query_params($conn, $sql_check_inventario, array($id_producto, $id_sucursal));
if ($row = pg_fetch_assoc($res_check)) {
    // Ya existe, actualizar cantidad
    $nueva_cantidad = $row['cantidad'] + $cantidad;
    $sql_update_inventario = "UPDATE inventario_sucursal SET cantidad = $1, precio_costo = $2, precio_venta = $3 WHERE id_producto = $4 AND id_sucursal = $5";
    pg_query_params($conn, $sql_update_inventario, array($nueva_cantidad, $precio_costo, $precio_venta, $id_producto, $id_sucursal));
} else {
    // No existe, insertar nuevo registro
    $sql_insert_inventario = "INSERT INTO inventario_sucursal (id_producto, id_sucursal, cantidad, precio_costo, precio_venta) VALUES ($1, $2, $3, $4, $5)";
    pg_query_params($conn, $sql_insert_inventario, array($id_producto, $id_sucursal, $cantidad, $precio_costo, $precio_venta));
}

// Si el ingreso se insertó correctamente, registrar en kardex
$precio_costo_unidad = $cantidad > 0 ? $precio_costo / $cantidad : 0;

$params_kardex = array(
    $id_producto,
    $cantidad,
    'INGRESO',
    $precio_costo_unidad,
    $fecha_ingreso,
    $id_usuario
);

$result_kardex = pg_query_params($conn, $sql_insertar_kardex, $params_kardex);

if (!$result_kardex) {
    // Si falla el kardex, no es crítico, solo log del error
    error_log("Error al registrar en kardex: " . pg_last_error($conn));
}

manejarResultadoConsulta($result, $conn, '../../ingreso.php?success=2', '../../ingreso.php?error=1');
?> 