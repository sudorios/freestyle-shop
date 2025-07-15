<?php
include_once '../../conexion/cone.php';
include_once __DIR__ . '/catalogo_queries.php';
global $conn;

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$estado = isset($_POST['estado']) ? ($_POST['estado'] === 'true' ? 'false' : 'true') : 'false'; // Cambia el estado

if ($id <= 0) {
    header('Location: ../../catalogo_producto.php?error=1&msg=ID inválido');
    exit;
}

$sql = "UPDATE catalogo_productos SET estado = $1 WHERE id = $2";
$result = pg_query_params($conn, $sql, [$estado, $id]);

if ($result) {
    header('Location: ../../catalogo_producto.php?success=2&msg=Estado actualizado');
    exit;
} else {
    header('Location: ../../catalogo_producto.php?error=1&msg=Error al actualizar');
    exit;
} 