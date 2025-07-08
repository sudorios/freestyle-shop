<?php
if (!isset($_GET['producto_id']) || !is_numeric($_GET['producto_id'])) {
    echo json_encode(['error' => 'ID de producto no válido']);
    exit;
}

include_once '../../conexion/cone.php';

$producto_id = intval($_GET['producto_id']);

$sql = "SELECT i.id, i.precio_venta, i.fecha_ingreso, i.ref 
        FROM ingreso i 
        WHERE i.id_producto = $1 AND i.estado = true 
        ORDER BY i.fecha_ingreso DESC 
        LIMIT 1";

$result = pg_query_params($conn, $sql, array($producto_id));

if ($result && pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    echo json_encode([
        'success' => true,
        'ingreso_id' => $row['id'],
        'precio_venta' => $row['precio_venta'],
        'fecha_ingreso' => $row['fecha_ingreso'],
        'ref' => $row['ref']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'No se encontró un ingreso válido para este producto'
    ]);
}
?> 