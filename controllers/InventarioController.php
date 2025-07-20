<?php
require_once __DIR__ . '/../models/Inventario.php';

class InventarioController {
    public function listar() {
        $id_sucursal = isset($_GET['id_sucursal']) && $_GET['id_sucursal'] !== '' ? intval($_GET['id_sucursal']) : null;
        $inventario = Inventario::obtenerTodos($id_sucursal);
        $sucursales = Inventario::obtenerSucursalesActivas();
        require __DIR__ . '/../views/inventario/listar.php';
    }

    public function exportarCSV() {
        $id_sucursal = isset($_GET['id_sucursal']) && $_GET['id_sucursal'] !== '' ? intval($_GET['id_sucursal']) : null;
        $inventario = Inventario::obtenerTodos($id_sucursal);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=inventario.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Producto', 'Sucursal', 'Cantidad', 'Fecha Actualización', 'Estado']);
        foreach ($inventario as $row) {
            fputcsv($output, [
                $row['nombre_producto'],
                $row['nombre_sucursal'],
                $row['cantidad'],
                isset($row['fecha_actualizacion']) ? date('d/m/Y H:i', strtotime($row['fecha_actualizacion'])) : '-',
                ($row['estado'] === true || $row['estado'] === 't' || $row['estado'] === 1 || $row['estado'] === '1') ? 'Activo' : ucfirst(strtolower($row['estado']))
            ]);
        }
        fclose($output);
        exit;
    }

    public function exportarPDF() {
        require_once __DIR__ . '/../vendor/autoload.php';
        $id_sucursal = isset($_GET['id_sucursal']) && $_GET['id_sucursal'] !== '' ? intval($_GET['id_sucursal']) : null;
        $inventario = Inventario::obtenerTodos($id_sucursal);
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        ob_start();
        require __DIR__ . '/../views/inventario/pdf_inventario.php';
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('inventario.pdf', ['Attachment' => true]);
        exit;
    }
} 