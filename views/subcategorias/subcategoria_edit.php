<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/subcategoria_queries.php';
require_once __DIR__ . '/subcategoria_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$id_subcategoria = filter_input(INPUT_POST, 'id_subcategoria');
verificarIdSubcategoria($id_subcategoria);

$sql_check = getSubcategoriaByIdQuery();
$result_check = pg_query_params($conn, $sql_check, [$id_subcategoria]);
verificarResultadoConsulta($result_check, '../../subcategoria.php', 3);

$nombre_subcategoria = trim(filter_input(INPUT_POST, 'nombre_subcategoria') ?? '');
$descripcion_subcategoria = trim(filter_input(INPUT_POST, 'descripcion_subcategoria') ?? '');
$id_categoria = filter_input(INPUT_POST, 'id_categoria');

$errores = validarCamposSubcategoria($nombre_subcategoria, $id_categoria);
manejarErrores($errores);

if (verificarExistenciaSubcategoria($conn, $nombre_subcategoria, $id_subcategoria)) {
    header('Location: ../../subcategoria.php?error=1&msg=' . urlencode('Ya existe una subcategoría con ese nombre'));
    exit();
}

$sql = updateSubcategoriaQuery();
$params = [$nombre_subcategoria, $descripcion_subcategoria, $id_categoria, $id_subcategoria];
$result = pg_query_params($conn, $sql, $params);

manejarResultadoConsulta(
    $result,
    $conn,
    '../../subcategoria.php?success=2',
    '../../subcategoria.php?error=1'
);
?>
