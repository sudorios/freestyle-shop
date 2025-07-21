<?php
require_once __DIR__ . '/../models/Kardex.php';

class KardexController {
    public function listar() {
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $id_sucursal = $_GET['id_sucursal'] ?? '';



        $errores = Kardex::validarFiltros($fecha_inicio, $fecha_fin);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=kardex&action=listar&error=1&msg=' . $msg);
            exit();
        }

        $kardex = Kardex::obtenerTodos($fecha_inicio, $fecha_fin, $id_sucursal);
        $sucursales = Kardex::obtenerSucursalesActivas();
        
        require __DIR__ . '/../views/kardex/listar.php';
    }

    public function exportarCsv() {
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';

        $kardex = Kardex::obtenerParaExportar($fecha_inicio, $fecha_fin);

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
            'id_usuario',
            'sucursal'
        ]);

        foreach ($kardex as $row) {
            fputcsv($output, [
                $row['id_kardex'],
                $row['id_producto'],
                $row['cantidad'],
                $row['tipo_movimiento'],
                $row['precio_costo'],
                $row['fecha_movimiento'],
                $row['id_usuario'],
                $row['nombre_sucursal'] ?? 'Sin sucursal'
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function exportarPdf() {
        require_once __DIR__ . '/../vendor/autoload.php';

        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';

        $kardex = Kardex::obtenerParaExportar($fecha_inicio, $fecha_fin);

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

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

        foreach ($kardex as $row) {
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
    }
} 