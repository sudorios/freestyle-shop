<?php
session_start();
header('Content-Type: application/json');
include_once 'carrito_utils.php';
include_once 'carrito_queries.php';
$carrito_id = obtener_carrito_id($conn);
$total_items = 0;
if ($carrito_id) {
    $sql = query_sumar_cantidad_items();
    $res = pg_query_params($conn, $sql, [$carrito_id]);
    $row = pg_fetch_assoc($res);
    $total_items = $row && $row['total'] ? (int)$row['total'] : 0;
}
echo json_encode(['total' => $total_items]); 