<?php
header('Content-Type: application/json');
include_once '../../conexion/cone.php';
include_once 'producto_queries.php';

$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
if (!$id_producto) {
    echo json_encode(['error' => 'ID de producto no válido']);
    exit;
}

$sql = getProductByIdQuery();
$result = pg_query_params($conn, $sql, [$id_producto]);
if ($result && pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Producto no encontrado']);
} 