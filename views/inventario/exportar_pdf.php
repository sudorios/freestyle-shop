<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/inventario_queries.php';
require_once __DIR__ . '/inventario_utils.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


$sql = getInventarioSucursalQuery();
$result = pg_query($conn, $sql);

$rows = [];
if ($result && pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

$html = '<h2 style="text-align:center;">Reporte de Inventario por Sucursal</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<thead>
<tr>
    <th>Producto</th>
    <th>Sucursal</th>
    <th>Cantidad</th>
    <th>Fecha Actualización</th>
    <th>Estado</th>
</tr>
</thead><tbody>';
foreach ($rows as $row) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['nombre_producto']) . '</td>
        <td>' . htmlspecialchars($row['nombre_sucursal']) . '</td>
        <td>' . htmlspecialchars($row['cantidad']) . '</td>
        <td>' . (isset($row['fecha_actualizacion']) ? date('d/m/Y H:i', strtotime($row['fecha_actualizacion'])) : '-') . '</td>
        <td>' . (($row['estado'] === true || $row['estado'] === 't' || $row['estado'] === 1 || $row['estado'] === '1') ? 'Activo' : 'Inactivo') . '</td>
    </tr>';
}
$html .= '</tbody></table>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream('inventario.pdf', ['Attachment' => true]);
exit; 