<?php
session_start();
header('Content-Type: application/json');
include_once './conexion/cone.php';
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$session_id = session_id();
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
$total_items = 0;
if ($carrito_id) {
    $sql = "SELECT SUM(cantidad) AS total FROM carrito_items WHERE carrito_id = $1 AND estado = 'activo'";
    $res = pg_query_params($conn, $sql, [$carrito_id]);
    $row = pg_fetch_assoc($res);
    $total_items = $row && $row['total'] ? (int)$row['total'] : 0;
}
echo json_encode(['total' => $total_items]); 