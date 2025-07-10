<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../conexion/cone.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['id'])) {
    die('Acceso denegado');
}

global $conn;
$sql = "SELECT p.id_pedido, u.nombre_usuario, p.fecha, p.total, p.estado, p.direccion_envio, p.metodo_pago, p.observaciones
        FROM pedido p
        LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
        ORDER BY p.fecha DESC";
$res = pg_query($conn, $sql);
$pedidos = [];
if ($res) {
    while ($row = pg_fetch_assoc($res)) {
        $pedidos[] = $row;
    }
}

$html = '<h2 style="text-align:center;">Reporte General de Pedidos</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<thead><tr>';
$html .= '<th>ID</th><th>Usuario</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Dirección</th><th>Método de Pago</th><th>Observaciones</th>';
$html .= '</tr></thead><tbody>';
foreach ($pedidos as $pedido) {
    $html .= '<tr>';
    $html .= '<td>'.htmlspecialchars($pedido['id_pedido']).'</td>';
    $html .= '<td>'.htmlspecialchars($pedido['nombre_usuario'] ?? '-').'</td>';
    $html .= '<td>'.htmlspecialchars($pedido['fecha']).'</td>';
    $html .= '<td>S/ '.number_format($pedido['total'],2).'</td>';
    $html .= '<td>'.htmlspecialchars($pedido['estado']).'</td>';
    $html .= '<td>'.htmlspecialchars($pedido['direccion_envio'] ?? '-').'</td>';
    $html .= '<td>'.htmlspecialchars($pedido['metodo_pago'] ?? '-').'</td>';
    $html .= '<td>'.htmlspecialchars($pedido['observaciones'] ?? '-').'</td>';
    $html .= '</tr>';
}
if (empty($pedidos)) {
    $html .= '<tr><td colspan="8" style="text-align:center;">No hay pedidos registrados.</td></tr>';
}
$html .= '</tbody></table>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream('reporte_pedidos.pdf', ['Attachment' => true]);
exit; 