<?php
require_once '../../vendor/autoload.php';
include_once '../../conexion/cone.php';
include_once 'transferencia_queries.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$fecha_inicio = isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] !== '' ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) && $_GET['fecha_fin'] !== '' ? $_GET['fecha_fin'] : '';
$filtro_origen = isset($_GET['origen']) && $_GET['origen'] !== '' ? $_GET['origen'] : '';
$filtro_destino = isset($_GET['destino']) && $_GET['destino'] !== '' ? $_GET['destino'] : '';

$where = [];
if ($fecha_inicio !== '') {
    $where[] = "t.fecha_transferencia >= '" . pg_escape_string($conn, $fecha_inicio) . "'";
}
if ($fecha_fin !== '') {
    $where[] = "t.fecha_transferencia <= '" . pg_escape_string($conn, $fecha_fin) . " 23:59:59'";
}
if ($filtro_origen !== '') {
    $where[] = "t.id_sucursal_origen = '" . pg_escape_string($conn, $filtro_origen) . "'";
}
if ($filtro_destino !== '') {
    $where[] = "t.id_sucursal_destino = '" . pg_escape_string($conn, $filtro_destino) . "'";
}
$where_sql = '';
if (count($where) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
}
$sql = getListadoTransferenciasQuery($where_sql);
$result = pg_query($conn, $sql);

$html = '<h2 style="text-align:center;">Listado de Transferencias</h2>';
$html .= '<table style="width:100%;border-collapse:collapse;font-size:12px;">';
$html .= '<thead><tr>';
$html .= '<th style="border:1px solid #ccc;padding:4px;">ID</th>';
$html .= '<th style="border:1px solid #ccc;padding:4px;">Producto</th>';
$html .= '<th style="border:1px solid #ccc;padding:4px;">Sucursal Origen</th>';
$html .= '<th style="border:1px solid #ccc;padding:4px;">Sucursal Destino</th>';
$html .= '<th style="border:1px solid #ccc;padding:4px;">Cantidad</th>';
$html .= '<th style="border:1px solid #ccc;padding:4px;">Fecha</th>';
$html .= '<th style="border:1px solid #ccc;padding:4px;">Usuario</th>';
$html .= '</tr></thead><tbody>';

while ($row = pg_fetch_assoc($result)) {
    $html .= '<tr>';
    $html .= '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($row['id']) . '</td>';
    $html .= '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($row['nombre_producto']) . '</td>';
    $html .= '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($row['sucursal_origen']) . '</td>';
    $html .= '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($row['sucursal_destino']) . '</td>';
    $html .= '<td style="border:1px solid #ccc;padding:4px;">' . $row['cantidad'] . '</td>';
    $html .= '<td style="border:1px solid #ccc;padding:4px;">' . date('d/m/Y H:i', strtotime($row['fecha_transferencia'])) . '</td>';
    $html .= '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($row['usuario']) . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('transferencias.pdf', ['Attachment' => true]);
exit; 