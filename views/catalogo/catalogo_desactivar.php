<?php
include_once '../../conexion/cone.php';
include_once 'catalogo_queries.php';
global $conn;

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    header('Location: ../../catalogo_producto.php?error=1&msg=ID inválido');
    exit;
}

$sql = setEstadoCatalogoProductoQuery();
$result = pg_query_params($conn, $sql, ['false', $id]);

if ($result) {
    header('Location: ../../catalogo_producto.php?success=1&msg=Producto desactivado');
    exit;
} else {
    header('Location: ../../catalogo_producto.php?error=1&msg=Error al desactivar');
    exit;
}
?> 