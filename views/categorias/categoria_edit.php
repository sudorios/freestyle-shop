<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/categoria_queries.php';
require_once __DIR__ . '/categoria_utils.php';

verificarMetodoPost();

$id_categoria = filter_input(INPUT_POST, 'id_categoria');
verificarIdCategoria($id_categoria);  

$sql_check       = getCategoriaByIdQuery();
$result_check    = pg_query_params($conn, $sql_check, [$id_categoria]);
verificarResultadoConsulta($result_check, '../../categoria.php', 3);

$nombre_categoria      = trim(filter_input(INPUT_POST, 'nombre_categoria') ?? '');
$descripcion_categoria = trim(filter_input(INPUT_POST, 'descripcion_categoria') ?? '');
$estado_categoria      = true; // Siempre activo al editar

$errores = validarCamposCategoria(
    $nombre_categoria,
    $descripcion_categoria,
    'true'
);
manejarErrores($errores);

if (verificarExistenciaCategoria($conn, $nombre_categoria, $id_categoria)) {
    header('Location: ../../categoria.php?error=1&msg=' . urlencode('Ya existe una categoría con ese nombre'));
    exit();
}

$sql    = updateCategoriaQuery();
$params = [$nombre_categoria, $descripcion_categoria, $estado_categoria, $id_categoria];
$result = pg_query_params($conn, $sql, $params);

manejarResultadoConsulta(
    $result,
    $conn,
    '../../categoria.php?success=2',
    '../../categoria.php?error=1'
);
