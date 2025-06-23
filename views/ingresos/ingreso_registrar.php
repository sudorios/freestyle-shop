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
    $id_usuario
);

$result = pg_query_params($conn, $sql_insertar_ingreso, $params);

if (!$result) {
    $error_msg = pg_last_error($conn);
    header('Location: ../../ingreso.php?error=1&msg=' . urlencode('Error al insertar: ' . $error_msg));
    exit();
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