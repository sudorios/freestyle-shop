<?php
include_once '../../conexion/cone.php';
include_once 'transferencia_queries.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=transferencias.csv');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'ID', 'Producto', 'Sucursal Origen', 'Sucursal Destino', 'Cantidad', 'Fecha Transferencia', 'Usuario'
]);

$sql = getListadoTransferenciasQuery();
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['nombre_producto'],
        $row['sucursal_origen'],
        $row['sucursal_destino'],
        $row['cantidad'],
        $row['fecha_transferencia'],
        $row['usuario']
    ]);
}

fclose($output);
exit; 