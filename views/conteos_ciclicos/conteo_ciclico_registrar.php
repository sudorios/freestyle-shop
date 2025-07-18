<?php
session_start();
include_once '../../conexion/cone.php';
include_once 'conteos_ciclicos_queries.php';
include_once 'conteo_ciclico_utils.php';

verificarMetodoPost();

$id_producto = $_POST['id_producto'] ?? '';
$id_sucursal = $_POST['id_sucursal'] ?? '';
$cantidad_real = $_POST['cantidad_real'] ?? '';
$comentarios = trim($_POST['comentarios'] ?? '');
$fecha_conteo = $_POST['fecha_conteo'] ?? date('Y-m-d');
$usuario_id = $_SESSION['id'] ?? null;

$sql_inv = getCantidadInventarioByProductoSucursalQuery();
$res_inv = pg_query_params($conn, $sql_inv, array($id_producto, $id_sucursal));
$cantidad_sistema = ($res_inv && pg_num_rows($res_inv) > 0) ? pg_fetch_result($res_inv, 0, 'cantidad') : 0;

$diferencia = $cantidad_real - $cantidad_sistema;
$estado_conteo = $_POST['estado_conteo'] ?? 'Pendiente';

$errores = validarCamposConteoCiclico($cantidad_real, $cantidad_sistema, $fecha_conteo);
if (!empty($errores)) {
    $msg = urlencode(implode(', ', $errores));
    header('Location: ../../conteo_ciclico.php?error=2&msg=' . $msg);
    exit();
}

$sql = insertConteoCiclicoQuery();
$params = array($id_producto, $id_sucursal, $cantidad_real, $cantidad_sistema, $diferencia, $fecha_conteo, $usuario_id, $comentarios, $estado_conteo);
$result = pg_query_params($conn, $sql, $params);

$estado_inventario = 'CUADRA';
if ($diferencia > 0) {
    $estado_inventario = 'SOBRA';
} elseif ($diferencia < 0) {
    $estado_inventario = 'FALTA';
}
$sql_update_inv = updateEstadoInventarioQuery();
$resultado_update = pg_query_params($conn, $sql_update_inv, array($estado_inventario, $id_producto, $id_sucursal));
if (!$resultado_update) {
    error_log('Error al actualizar estado inventario: ' . pg_last_error($conn));
} else {
    $filas_afectadas = pg_affected_rows($resultado_update);
    error_log('UPDATE inventario_sucursal OK: estado=' . $estado_inventario . ', producto=' . $id_producto . ', sucursal=' . $id_sucursal . ', filas afectadas=' . $filas_afectadas);
}

manejarResultadoConsulta($result, $conn, '../../conteo_ciclico.php?id_producto=' . $id_producto . '&id_sucursal=' . $id_sucursal . '&success=2', '../../conteo_ciclico.php?id_producto=' . $id_producto . '&id_sucursal=' . $id_sucursal . '&error=1');
?> 