<?php
require_once '../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

function generar_boleta_pdf($id_pedido, $usuario_nombre, $fecha_pedido, $direccion_envio, $productos, $total) {
    $tabla_productos = '';
    foreach ($productos as $prod) {
        $tabla_productos .= '<tr>' .
            '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($prod['id_producto']) . '</td>' .
            '<td style="border:1px solid #ccc;padding:4px;">' . htmlspecialchars($prod['cantidad']) . '</td>' .
            '<td style="border:1px solid #ccc;padding:4px;">S/ ' . number_format($prod['precio_unitario'], 2) . '</td>' .
            '<td style="border:1px solid #ccc;padding:4px;">S/ ' . number_format($prod['cantidad'] * $prod['precio_unitario'], 2) . '</td>' .
            '</tr>';
    }

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
        '<p style="text-align:right;margin-top:10px;"><strong>Total: S/ ' . number_format($total, 2) . '</strong></p>';

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdf_output = $dompdf->output();
    $pdf_path = '../../boletas/boleta_' . $id_pedido . '.pdf';
    file_put_contents($pdf_path, $pdf_output);
    return $pdf_path;
} 