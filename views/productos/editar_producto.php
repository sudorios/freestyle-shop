<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/producto_queries.php';
require_once __DIR__ . '/producto_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$ref_producto = trim(filter_input(INPUT_POST, 'ref_producto'));
verificarRefProducto($ref_producto);

$sql_check = getProductByRefQuery();
$result_check = pg_query_params($conn, $sql_check, [$ref_producto]);
verificarResultadoConsulta($result_check, '../../producto.php', 3);

$nombre = trim(filter_input(INPUT_POST, 'nombre_producto') ?? '');
$descripcion = trim(filter_input(INPUT_POST, 'descripcion_producto') ?? '');
$id_subcategoria = filter_input(INPUT_POST, 'id_subcategoria');
$talla = trim(filter_input(INPUT_POST, 'talla_producto') ?? '');

$errores = validarCamposProducto($ref_producto, $nombre, $id_subcategoria, $talla);
if (!empty($errores)) {
    $msg = urlencode(implode(', ', $errores));
    header('Location: ../../producto.php?error=2&msg=' . $msg);
    exit();
}

$sql = updateProductByRefQuery();
$params = [$nombre, $descripcion, $id_subcategoria, $talla, $ref_producto];
$result = pg_query_params($conn, $sql, $params);

manejarResultadoConsulta(
    $result,
    $conn,
    '../../producto.php?success=2',
    '../../producto.php?error=1'
);
?> 