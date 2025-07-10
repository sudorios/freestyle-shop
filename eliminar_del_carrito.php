<?php
session_start();
header('Content-Type: application/json');
include_once './conexion/cone.php';

$item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
if ($item_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$session_id = session_id();

// Buscar carrito del usuario/sesión
if ($usuario_id) {
    $sql = "SELECT id FROM carrito WHERE usuario_id = $1";
    $params = [$usuario_id];
} else {
    $sql = "SELECT id FROM carrito WHERE session_id = $1";
    $params = [$session_id];
}
$result = pg_query_params($conn, $sql, $params);
$row = pg_fetch_assoc($result);
$carrito_id = $row ? $row['id'] : null;
if (!$carrito_id) {
    echo json_encode(['success' => false, 'error' => 'Carrito no encontrado']);
    exit;
}
// Verificar que el item pertenezca al carrito
$sql = "SELECT id FROM carrito_items WHERE id = $1 AND carrito_id = $2 AND estado = 'activo'";
$res = pg_query_params($conn, $sql, [$item_id, $carrito_id]);
if (!pg_fetch_assoc($res)) {
    echo json_encode(['success' => false, 'error' => 'Item no encontrado en tu carrito']);
    exit;
}
// Eliminar (marcar como inactivo)
$sql = "UPDATE carrito_items SET estado = 'eliminado' WHERE id = $1";
pg_query_params($conn, $sql, [$item_id]);
echo json_encode(['success' => true]); 