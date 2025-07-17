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

$sql_insertar_ingreso = getInsertarIngresoQuery();

$result = pg_query_params($conn, $sql_insertar_ingreso, $params);

if (!$result) {
    $error_msg = pg_last_error($conn);
    header('Location: ../../ingreso.php?error=1&msg=' . urlencode('Error al insertar: ' . $error_msg));
    exit();
}

$sql_check_inventario = getCantidadInventarioSucursalQuery();
$res_check = pg_query_params($conn, $sql_check_inventario, array($id_producto, $id_sucursal));

if (!$res_check) {
    error_log('Error al verificar inventario: ' . pg_last_error($conn));
    header('Location: ../../ingreso.php?error=1&msg=' . urlencode('Error al verificar inventario: ' . pg_last_error($conn)));
    exit();
}

if ($row = pg_fetch_assoc($res_check)) {
    $nueva_cantidad = $row['cantidad'] + $cantidad;
    $sql_update_inventario = updateInventarioSucursalQuery();
    $result_update = pg_query_params($conn, $sql_update_inventario, array($nueva_cantidad, $id_producto, $id_sucursal));
    
    if (!$result_update) {
        error_log('Error al actualizar inventario: ' . pg_last_error($conn));
        header('Location: ../../ingreso.php?error=1&msg=' . urlencode('Error al actualizar inventario: ' . pg_last_error($conn)));
        exit();
    } else {
        error_log('Inventario actualizado: producto=' . $id_producto . ', sucursal=' . $id_sucursal . ', cantidad=' . $nueva_cantidad);
    }
} else {
    $sql_insert_inventario = insertInventarioSucursalQuery();
    $result_insert = pg_query_params($conn, $sql_insert_inventario, array($id_producto, $id_sucursal, $cantidad));
    
    if (!$result_insert) {
        error_log('Error al insertar inventario: ' . pg_last_error($conn));
        header('Location: ../../ingreso.php?error=1&msg=' . urlencode('Error al insertar inventario: ' . pg_last_error($conn)));
        exit();
    } else {
        error_log('Inventario insertado: producto=' . $id_producto . ', sucursal=' . $id_sucursal . ', cantidad=' . $cantidad);
    }
}

$precio_costo_unidad = $cantidad > 0 ? $precio_costo / $cantidad : 0;

$sql_insertar_kardex = InsertarKardexQuery();

$params_kardex = array(
    $id_producto,
    $cantidad,
    'INGRESO',
    $precio_costo_unidad,
    $fecha_ingreso,
    $id_usuario,
    $id_sucursal
);

$result_kardex = pg_query_params($conn, $sql_insertar_kardex, $params_kardex);

if (!$result_kardex) {
    error_log("Error al registrar en kardex: " . pg_last_error($conn));
}

manejarResultadoConsulta($result, $conn, '../../ingreso.php?success=2', '../../ingreso.php?error=1');
?> 