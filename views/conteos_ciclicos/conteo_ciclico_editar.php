<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/conteos_ciclicos_queries.php';
require_once __DIR__ . '/conteo_ciclico_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$id_conteo = filter_input(INPUT_POST, 'id_conteo', FILTER_VALIDATE_INT);
verificarIdConteo($id_conteo);

$id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
$id_sucursal = filter_input(INPUT_POST, 'id_sucursal', FILTER_VALIDATE_INT);

$cantidad_real = trim(filter_input(INPUT_POST, 'cantidad_real'));
$cantidad_sistema = trim(filter_input(INPUT_POST, 'cantidad_sistema'));
$fecha_conteo = trim(filter_input(INPUT_POST, 'fecha_conteo'));
$estado_conteo = trim(filter_input(INPUT_POST, 'estado_conteo'));
$comentarios = trim(filter_input(INPUT_POST, 'comentarios'));
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

$diferencia = is_numeric($cantidad_real) && is_numeric($cantidad_sistema) ? ($cantidad_real - $cantidad_sistema) : null;

$sql_get = getConteoCiclicoByIdQuery();
$res_get = pg_query_params($conn, $sql_get, [$id_conteo]);
$fecha_ajuste_actual = null;
$estado_anterior = null;
if ($res_get && pg_num_rows($res_get) > 0) {
    $row = pg_fetch_assoc($res_get);
    $fecha_ajuste_actual = $row['fecha_ajuste'];
    $estado_anterior = $row['estado_conteo'];
}

$fecha_ajuste = date('Y-m-d');

$errores = validarCamposConteoCiclico($cantidad_real, $cantidad_sistema, $fecha_conteo);
if (!empty($errores)) {
    $msg = urlencode(implode(', ', $errores));
    header('Location: ../../conteo_ciclico.php?error=2&msg=' . $msg);
    exit();
}

$sql = updateConteoCiclicoQuery();
$params = [
    $cantidad_real,
    $cantidad_sistema,
    $diferencia,
    $fecha_conteo,
    $usuario_id,
    $comentarios,
    $estado_conteo,
    $fecha_ajuste,
    $id_conteo
];
$result = pg_query_params($conn, $sql, $params);

manejarResultadoConsulta(
    $result,
    $conn,
    "../../conteo_ciclico.php?id_producto=$id_producto&id_sucursal=$id_sucursal&success=2",
    "../../conteo_ciclico.php?id_producto=$id_producto&id_sucursal=$id_sucursal&error=1"
);
?> 