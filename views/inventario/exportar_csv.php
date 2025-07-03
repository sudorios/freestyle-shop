<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/inventario_queries.php';
require_once __DIR__ . '/inventario_utils.php';

verificarSesionAdminInventario();

$sql = getInventarioSucursalQuery();
$result = pg_query($conn, $sql);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inventario.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['Producto', 'Sucursal', 'Cantidad', 'Fecha Actualización', 'Estado']);

while ($row = pg_fetch_assoc($result)) {
    fputcsv($output, [
        $row['nombre_producto'],
        $row['nombre_sucursal'],
        $row['cantidad'],
        isset($row['fecha_actualizacion']) ? date('d/m/Y H:i', strtotime($row['fecha_actualizacion'])) : '-',
        ($row['estado'] === true || $row['estado'] === 't' || $row['estado'] === 1 || $row['estado'] === '1') ? 'Activo' : 'Inactivo'
    ]);
}
fclose($output);
exit; 