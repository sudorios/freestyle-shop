<?php
require_once '../../vendor/autoload.php';
require_once '../../conexion/cone.php';
require_once __DIR__ . '/kardex_queries.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

$fecha_inicio = isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] !== '' ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) && $_GET['fecha_fin'] !== '' ? $_GET['fecha_fin'] : null;

$where = [];
if ($fecha_inicio) {
    $where[] = "k.fecha_movimiento >= '" . pg_escape_string($conn, $fecha_inicio) . "'";
}
if ($fecha_fin) {
    $where[] = "k.fecha_movimiento <= '" . pg_escape_string($conn, $fecha_fin) . "'";
}
$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = getKardexExportPdfQuery($where_sql);
$result = pg_query($conn, $sql);

$html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kardex PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Reporte de Kardex</h2>';
if ($fecha_inicio || $fecha_fin) {
    $html .= '<p style="text-align:center;">';
    if ($fecha_inicio) {
        $html .= 'Desde: ' . htmlspecialchars($fecha_inicio) . ' ';
    }
    if ($fecha_fin) {
        $html .= 'Hasta: ' . htmlspecialchars($fecha_fin);
    }
    $html .= '</p>';
}
$html .= '<table>
        <thead>
            <tr>
                <th>ID Kardex</th>
                <th>ID Producto</th>
                <th>Cantidad</th>
                <th>Tipo Movimiento</th>
                <th>Precio Costo</th>
                <th>Fecha Movimiento</th>
                <th>ID Usuario</th>
                <th>Sucursal</th>
            </tr>
        </thead>
        <tbody>';

while ($row = pg_fetch_assoc($result)) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['id_kardex']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['id_producto']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['cantidad']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['tipo_movimiento']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['precio_costo']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['fecha_movimiento']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['id_usuario']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['nombre_sucursal'] ?? 'Sin sucursal') . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody></table></body></html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('kardex_' . date('Y-m-d_H-i-s') . '.pdf', array('Attachment' => true));
exit; 