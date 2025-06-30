<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/categoria_queries.php';
require_once __DIR__ . '/categoria_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$id_categoria = filter_input(INPUT_POST, 'id_categoria');
verificarIdCategoria($id_categoria);

$sql = deleteCategoriaQuery();
$params = [$id_categoria];
$result = pg_query_params($conn, $sql, $params);

manejarResultadoConsulta($result, $conn, '../../categoria.php?success=3', '../../categoria.php?error=1'); 