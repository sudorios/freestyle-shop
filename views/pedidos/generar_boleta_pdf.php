<?php
require_once '../../vendor/autoload.php';
require_once '../../conexion/cone.php';
use Dompdf\Dompdf;
use Dompdf\Options;

function generar_boleta_pdf($id_pedido, $usuario_nombre, $fecha_pedido, $direccion_envio, $productos, $total) {
    $tabla_productos = '';
    $subtotal = 0;
    foreach ($productos as $prod) {
        $prod_subtotal = $prod['cantidad'] * $prod['precio_unitario'];
        $subtotal += $prod_subtotal;
        $tabla_productos .= '<tr>' .
            '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($prod['id_producto']) . '</td>' .
            '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($prod['cantidad']) . '</td>' .
            '<td style="border:1px solid #ccc;padding:4px;">S/ ' . number_format($prod['precio_unitario'], 2) . '</td>' .
            '<td style="border:1px solid #ccc;padding:4px;">S/ ' . number_format($prod_subtotal, 2) . '</td>' .
            '</tr>';
    }

    $igv = $total - $subtotal;
    if ($igv < 0 || is_nan($igv)) $igv = 0; 

    $html = '<h2 style="text-align:center;">Boleta de Pedido</h2>' .
        '<p><strong>N° Pedido:</strong> ' . $id_pedido . '</p>' .
        '<p><strong>Fecha:</strong> ' . $fecha_pedido . '</p>' .
        '<p><strong>Cliente:</strong> ' . htmlspecialchars($usuario_nombre) . '</p>' .
        '<p><strong>Dirección de envío:</strong> ' . htmlspecialchars($direccion_envio) . '</p>' .
        '<table style="width:100%;border-collapse:collapse;margin-top:10px;">
            <thead>
                <tr>
                    <th style="border:1px solid #ccc;padding:4px;">ID Producto</th>
                    <th style="border:1px solid #ccc;padding:4px;">Cantidad</th>
                    <th style="border:1px solid #ccc;padding:4px;">Precio Unitario</th>
                    <th style="border:1px solid #ccc;padding:4px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>' . $tabla_productos . '</tbody>
        </table>' .
        '<p style="text-align:right;margin-top:10px;"><strong>Subtotal: S/ ' . number_format($subtotal, 2) . '</strong></p>' .
        '<p style="text-align:right;"><strong>IGV (18%): S/ ' . number_format($igv, 2) . '</strong></p>' .
        '<p style="text-align:right;"><strong>Total: S/ ' . number_format($total, 2) . '</strong></p>';

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('boleta_' . $id_pedido . '.pdf', ['Attachment' => true]);
}

if (php_sapi_name() !== 'cli' && isset($_GET['id_pedido'])) {
    session_start();
    if (!isset($_SESSION['id']) && !isset($_SESSION['id_usuario'])) {
        die('Acceso denegado');
    }
    $id_pedido = intval($_GET['id_pedido']);
    if ($id_pedido <= 0) {
        die('ID de pedido inválido');
    }
    global $conn;
    $sql = "SELECT p.id_pedido, u.nombre_usuario, p.fecha, p.total, p.direccion_envio FROM pedido p LEFT JOIN usuario u ON p.id_usuario = u.id_usuario WHERE p.id_pedido = $1";
    $res = pg_query_params($conn, $sql, [$id_pedido]);
    $pedido = pg_fetch_assoc($res);
    if (!$pedido) {
        die('Pedido no encontrado');
    }
    $sql_det = "SELECT dp.id_producto, dp.cantidad, dp.precio_unitario FROM detalle_pedido dp WHERE dp.id_pedido = $1";
    $res_det = pg_query_params($conn, $sql_det, [$id_pedido]);
    $productos = [];
    if ($res_det) {
        while ($row = pg_fetch_assoc($res_det)) {
            $productos[] = $row;
        }
    }
    $usuario_nombre = $pedido['nombre_usuario'] ?? 'Cliente';
    $fecha_pedido = $pedido['fecha'];
    $direccion_envio = $pedido['direccion_envio'];
    $total = $pedido['total'];
    generar_boleta_pdf($id_pedido, $usuario_nombre, $fecha_pedido, $direccion_envio, $productos, $total);
    exit;
} 