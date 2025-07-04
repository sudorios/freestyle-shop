<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=kardex.csv');
$output = fopen('php://output', 'w');
fputcsv($output, [
    'id_kardex',
    'id_producto',
    'cantidad',
    'tipo_movimiento',
    'precio_costo',
    'fecha_movimiento',
    'id_usuario'
]);

$sql = "SELECT id_kardex, id_producto, cantidad, tipo_movimiento, precio_costo, fecha_movimiento, id_usuario FROM kardex ORDER BY id_kardex ASC";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id_kardex'],
        $row['id_producto'],
        $row['cantidad'],
        $row['tipo_movimiento'],
        $row['precio_costo'],
        $row['fecha_movimiento'],
        $row['id_usuario']
    ]);
}
fclose($output);
exit; 