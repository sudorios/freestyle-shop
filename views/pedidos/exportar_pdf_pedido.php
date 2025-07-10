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

$id_pedido = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_pedido <= 0) {
    die('ID de pedido inválido');
}

global $conn;
$sql = "SELECT p.id_pedido, u.nombre_usuario, p.fecha, p.total, p.estado, p.direccion_envio, p.metodo_pago, p.observaciones
        FROM pedido p
        LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
        WHERE p.id_pedido = $1";
$res = pg_query_params($conn, $sql, [$id_pedido]);
$pedido = pg_fetch_assoc($res);
if (!$pedido) {
    die('Pedido no encontrado');
}

$sql_det = "SELECT dp.id_detalle, dp.cantidad, dp.precio_unitario, dp.subtotal, p.nombre_producto, p.talla_producto
            FROM detalle_pedido dp
            JOIN producto p ON dp.id_producto = p.id_producto
            WHERE dp.id_pedido = $1";
$res_det = pg_query_params($conn, $sql_det, [$id_pedido]);
$productos = [];
if ($res_det) {
    while ($row = pg_fetch_assoc($res_det)) {
        $productos[] = $row;
    }
}

$html = '<h2 style="text-align:center;">Detalle del Pedido #'.htmlspecialchars($pedido['id_pedido']).'</h2>';
$html .= '<p><strong>Usuario:</strong> '.htmlspecialchars($pedido['nombre_usuario'] ?? '-').'</p>';
$html .= '<p><strong>Fecha:</strong> '.htmlspecialchars($pedido['fecha']).'</p>';
$html .= '<p><strong>Estado:</strong> '.htmlspecialchars($pedido['estado']).'</p>';
$html .= '<p><strong>Total:</strong> S/ '.number_format($pedido['total'],2).'</p>';
$html .= '<p><strong>Dirección de envío:</strong> '.htmlspecialchars($pedido['direccion_envio'] ?? '-').'</p>';
$html .= '<p><strong>Método de pago:</strong> '.htmlspecialchars($pedido['metodo_pago'] ?? '-').'</p>';
$html .= '<p><strong>Observaciones:</strong> '.htmlspecialchars($pedido['observaciones'] ?? '-').'</p>';
$html .= '<h3 style="margin-top:20px;">Productos</h3>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
$html .= '<thead><tr><th>Producto</th><th>Talla</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>';
foreach ($productos as $prod) {
    $html .= '<tr>';
    $html .= '<td>'.htmlspecialchars($prod['nombre_producto']).'</td>';
    $html .= '<td>'.htmlspecialchars($prod['talla_producto'] ?? '-').'</td>';
    $html .= '<td>'.htmlspecialchars($prod['cantidad']).'</td>';
    $html .= '<td>S/ '.number_format($prod['precio_unitario'],2).'</td>';
    $html .= '<td>S/ '.number_format($prod['subtotal'],2).'</td>';
    $html .= '</tr>';
}
if (empty($productos)) {
    $html .= '<tr><td colspan="5" style="text-align:center;">No hay productos en este pedido.</td></tr>';
}
$html .= '</tbody></table>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream('pedido_'.$pedido['id_pedido'].'.pdf', ['Attachment' => true]);
exit; 