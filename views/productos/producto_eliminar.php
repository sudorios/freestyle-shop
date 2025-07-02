<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/producto_queries.php';
require_once __DIR__ . '/producto_utils.php';

verificarSesionAdmin();
verificarMetodoPost();

$id_producto = filter_input(INPUT_POST, 'id_producto');
verificarIdProducto($id_producto);

$sql = deleteProductQuery();
$result = pg_query_params($conn, $sql, [$id_producto]);

if ($result) {
    header('Location: ../../producto.php?success=2');
    exit();
} else {
    $error_msg = pg_last_error($conn);
    header('Location: ../../producto.php?error=1&msg=' . urlencode('Error al eliminar: ' . $error_msg));
    exit();
} 