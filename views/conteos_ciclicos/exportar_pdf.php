<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/conteos_ciclicos_queries.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
$id_sucursal = isset($_GET['id_sucursal']) ? intval($_GET['id_sucursal']) : 0;
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
$usuario = isset($_GET['usuario']) ? trim($_GET['usuario']) : '';
$estado = isset($_GET['estado']) ? trim($_GET['estado']) : '';

if ($id_producto <= 0 || $id_sucursal <= 0) {
    die('Parámetros inválidos.');
}

$sql = getConteosCiclicosFiltradosQuery($fecha_desde, $fecha_hasta, $usuario, $estado);
$params = [$id_producto, $id_sucursal];
if ($fecha_desde && $fecha_hasta) {
    $params[] = $fecha_desde;
    $params[] = $fecha_hasta;
} elseif ($fecha_desde) {
    $params[] = $fecha_desde;
} elseif ($fecha_hasta) {
    $params[] = $fecha_hasta;
}
if ($usuario) {
    $params[] = "%$usuario%";
}
if ($estado) {
    $params[] = $estado;
}
$result = pg_query_params($conn, $sql, $params);

$rows = [];
if ($result && pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

$html = '<h2 style="text-align:center;">Reporte de Conteos Cíclicos</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<thead>
<tr>
    <th>ID</th>
    <th>Cantidad Real</th>
    <th>Cantidad Sistema</th>
    <th>Diferencia</th>
    <th>Fecha Conteo</th>
    <th>Usuario</th>
    <th>Estado</th>
    <th>Fecha Ajuste</th>
    <th>Comentarios</th>
</tr>
</thead><tbody>';
foreach ($rows as $row) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['id_conteo']) . '</td>
        <td>' . htmlspecialchars($row['cantidad_real']) . '</td>
        <td>' . htmlspecialchars($row['cantidad_sistema']) . '</td>
        <td>' . htmlspecialchars($row['diferencia']) . '</td>
        <td>' . date('d/m/Y', strtotime($row['fecha_conteo'])) . '</td>
        <td>' . htmlspecialchars($row['nombre_usuario'] ?? $row['usuario_id']) . '</td>
        <td>' . ucfirst(htmlspecialchars($row['estado_conteo'])) . '</td>
        <td>' . ($row['fecha_ajuste'] ? date('d/m/Y', strtotime($row['fecha_ajuste'])) : '-') . '</td>
        <td>' . htmlspecialchars($row['comentarios']) . '</td>
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

$dompdf->stream('conteos_ciclicos.pdf', ['Attachment' => true]);
exit; 