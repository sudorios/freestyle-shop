<?php
require_once __DIR__ . '/../models/Transferencia.php';

class TransferenciaController {
    public function listar() {
        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin' => $_GET['fecha_fin'] ?? '',
            'origen' => $_GET['origen'] ?? '',
            'destino' => $_GET['destino'] ?? ''
        ];
        $transferencias = Transferencia::obtenerTodas($filtros);
        $sucursales = Transferencia::obtenerSucursales();
        require __DIR__ . '/../views/transferencias/listar.php';
    }

    public function registrar() {
        if (isset($_GET['ajax_stock']) && $_GET['ajax_stock'] == '1' && isset($_GET['producto']) && isset($_GET['sucursal'])) {
            $stock = Transferencia::obtenerStockProductoSucursal($_GET['producto'], $_GET['sucursal']);
            echo $stock;
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['id'])) {
                header('Location: index.php?controller=usuario&action=login&error=2&msg=Debes iniciar sesión para registrar transferencias');
                exit();
            }
            $origen = $_POST['origen'] ?? '';
            $destino = $_POST['destino'] ?? '';
            $producto = $_POST['producto'] ?? '';
            $cantidad = $_POST['cantidad'] ?? 1;
            $fecha = $_POST['fecha'] ?? '';
            $id_usuario = $_SESSION['id'];
            $errores = Transferencia::validarCampos($origen, $destino, $producto, $cantidad, $fecha);
            if (!empty($errores)) {
                $msg = urlencode(implode(', ', $errores));
                header('Location: index.php?controller=transferencia&action=registrar&error=1&msg=' . $msg);
                exit();
            }
            $ok = Transferencia::registrar($origen, $destino, $producto, $cantidad, $fecha, $id_usuario);
            if ($ok === true) {
                header('Location: index.php?controller=transferencia&action=listar&success=2');
                exit();
            } else {
                $msg = urlencode($ok);
                header('Location: index.php?controller=transferencia&action=registrar&error=1&msg=' . $msg);
                exit();
            }
        }
        $sucursales = Transferencia::obtenerSucursales();
        $productos = Transferencia::obtenerProductosActivos();
        require __DIR__ . '/../views/transferencias/registrar.php';
    }

    public function exportarCSV() {
        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin' => $_GET['fecha_fin'] ?? '',
            'origen' => $_GET['origen'] ?? '',
            'destino' => $_GET['destino'] ?? ''
        ];
        $transferencias = Transferencia::obtenerTodas($filtros);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=transferencias.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Producto', 'Sucursal Origen', 'Sucursal Destino', 'Cantidad', 'Fecha Transferencia', 'Usuario']);
        foreach ($transferencias as $row) {
            fputcsv($output, [
                $row['id'],
                $row['nombre_producto'],
                $row['sucursal_origen'],
                $row['sucursal_destino'],
                $row['cantidad'],
                $row['fecha_transferencia'],
                $row['usuario']
            ]);
        }
        fclose($output);
        exit;
    }

    public function exportarPDF() {
        $filtros = [
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin' => $_GET['fecha_fin'] ?? '',
            'origen' => $_GET['origen'] ?? '',
            'destino' => $_GET['destino'] ?? ''
        ];
        $transferencias = Transferencia::obtenerTodas($filtros);
        require_once __DIR__ . '/../vendor/autoload.php';
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
        foreach ($transferencias as $row) {
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
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('transferencias.pdf', ['Attachment' => true]);
        exit;
    }
} 