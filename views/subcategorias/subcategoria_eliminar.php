<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/subcategoria_utils.php';
require_once __DIR__ . '/subcategoria_queries.php';

verificarSesionAdmin();

$id = $_POST['id'] ?? $_POST['id_subcategoria'] ?? $_GET['id'] ?? $_GET['id_subcategoria'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: ../../subcategoria.php?error=2');
    exit();
}

$sql = deleteSubcategoriaQuery();
$result = pg_query_params($conn, $sql, array($id));

manejarResultadoConsulta($result, $conn, '../../subcategoria.php?success=2', '../../subcategoria.php?error=1');
?> 