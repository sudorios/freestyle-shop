<?php
session_start();
header('Content-Type: application/json');
include_once 'carrito_utils.php';
include_once 'carrito_queries.php';

$item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
if ($item_id <= 0 || $cantidad < 1) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}
$carrito_id = obtener_carrito_id($conn);
if (!$carrito_id) {
    echo json_encode(['success' => false, 'error' => 'Carrito no encontrado']);
    exit;
}
$sql = query_obtener_item_por_id();
$res = pg_query_params($conn, $sql, [$item_id, $carrito_id]);
if (!pg_fetch_assoc($res)) {
    echo json_encode(['success' => false, 'error' => 'Item no encontrado en tu carrito']);
    exit;
}
$sql = query_actualizar_cantidad_item();
pg_query_params($conn, $sql, [$cantidad, $item_id]);
echo json_encode(['success' => true]); 