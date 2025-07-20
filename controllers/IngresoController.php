<?php
require_once __DIR__ . '/../models/Ingreso.php';

class IngresoController {
    public function listar() {
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $where_sql = '';
        if ($fecha_inicio || $fecha_fin) {
            $w = [];
            if ($fecha_inicio) $w[] = "i.fecha_ingreso >= '" . pg_escape_string(Database::getConexion(), $fecha_inicio) . "'";
            if ($fecha_fin) $w[] = "i.fecha_ingreso <= '" . pg_escape_string(Database::getConexion(), $fecha_fin) . "'";
            $where_sql = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        }
        $ingresos = Ingreso::obtenerTodos($where_sql);
        $sucursales = Ingreso::obtenerSucursalesActivas();
        $productos = Ingreso::obtenerProductosActivos();
        require __DIR__ . '/../views/ingresos/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=ingreso&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        session_start();
        $data = [
            'ref' => trim($_POST['ref'] ?? ''),
            'id_producto' => $_POST['id_producto'] ?? '',
            'precio_costo' => $_POST['precio_costo'] ?? '',
            'precio_costo_igv_paquete' => $_POST['precio_costo_igv_paquete'] ?? '',
            'precio_venta' => $_POST['precio_venta'] ?? '',
            'utilidad_esperada_total' => $_POST['utilidad_esperada_total'] ?? '',
            'utilidad_neta_total' => $_POST['utilidad_neta_total'] ?? '',
            'cantidad' => $_POST['cantidad'] ?? 1,
            'fecha_ingreso' => $_POST['fecha_ingreso'] ?? '',
            'id_usuario' => $_SESSION['id'] ?? null,
            'id_sucursal' => $_POST['id_sucursal'] ?? ''
        ];
        $errores = Ingreso::validarCampos($data);
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=ingreso&action=listar&error=1&msg=' . $msg);
            exit();
        }
        $ok = Ingreso::registrar($data);
        if ($ok) {
            header('Location: index.php?controller=ingreso&action=listar&success=2');
        } else {
            $msg = urlencode(pg_last_error());
            header('Location: index.php?controller=ingreso&action=listar&error=1&msg=' . $msg);
        }
        exit();
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=ingreso&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        session_start();
        $id_ingreso = $_POST['id_ingreso'] ?? '';
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
        $cantidad = $_POST['cantidad_ingreso'] ?? '';
        $precio_costo_igv = $_POST['precio_costo_igv'] ?? '';
        $precio_venta = $_POST['precio_venta'] ?? '';
        $errores = [];
        if (!$id_ingreso) $errores[] = 'ID de ingreso inválido';
        if (!$fecha_ingreso) $errores[] = 'Fecha requerida';
        if (!$cantidad || $cantidad <= 0) $errores[] = 'Cantidad inválida';
        if ($precio_costo_igv === '' || !is_numeric($precio_costo_igv)) $errores[] = 'Precio costo IGV inválido';
        if ($precio_venta === '' || !is_numeric($precio_venta)) $errores[] = 'Precio venta inválido';
        if (!empty($errores)) {
            $msg = urlencode(implode(', ', $errores));
            header('Location: index.php?controller=ingreso&action=listar&error=2&msg=' . $msg);
            exit();
        }
        $ok = Ingreso::actualizar([
            'id_ingreso' => $id_ingreso,
            'fecha_ingreso' => $fecha_ingreso,
            'cantidad' => $cantidad,
            'precio_costo_igv' => $precio_costo_igv,
            'precio_venta' => $precio_venta
        ]);
        if ($ok === true) {
            header('Location: index.php?controller=ingreso&action=listar&success=2');
        } else {
            $msg = urlencode($ok);
            header('Location: index.php?controller=ingreso&action=listar&error=1&msg=' . $msg);
        }
        exit();
    }

    public function exportarCSV() {
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $where_sql = '';
        if ($fecha_inicio || $fecha_fin) {
            $w = [];
            if ($fecha_inicio) $w[] = "i.fecha_ingreso >= '" . pg_escape_string(Database::getConexion(), $fecha_inicio) . "'";
            if ($fecha_fin) $w[] = "i.fecha_ingreso <= '" . pg_escape_string(Database::getConexion(), $fecha_fin) . "'";
            $where_sql = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        }
        $ingresos = Ingreso::obtenerTodos($where_sql);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=ingresos.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Referencia', 'Producto', 'Sucursal', 'Cantidad', 'Fecha Ingreso', 'Usuario', 'Precio Costo IGV', 'Precio Venta', 'Utilidad Esperada', 'Utilidad Neta']);
        foreach ($ingresos as $row) {
            fputcsv($output, [
                $row['id_ingreso'] ?? $row['id'],
                $row['ref'],
                $row['nombre_producto'] . ($row['talla_producto'] ? ' (' . $row['talla_producto'] . ')' : ''),
                $row['nombre_sucursal'] ?? 'Sin sucursal',
                $row['cantidad'],
                $row['fecha_ingreso'],
                $row['usuario'],
                $row['precio_costo_igv'],
                $row['precio_venta'],
                $row['utilidad_esperada'],
                $row['utilidad_neta']
            ]);
        }
        fclose($output);
        exit;
    }

    public function exportarPDF() {
        require_once __DIR__ . '/../vendor/autoload.php';
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $where_sql = '';
        if ($fecha_inicio || $fecha_fin) {
            $w = [];
            if ($fecha_inicio) $w[] = "i.fecha_ingreso >= '" . pg_escape_string(Database::getConexion(), $fecha_inicio) . "'";
            if ($fecha_fin) $w[] = "i.fecha_ingreso <= '" . pg_escape_string(Database::getConexion(), $fecha_fin) . "'";
            $where_sql = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        }
        $ingresos = Ingreso::obtenerTodos($where_sql);
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        ob_start();
        require __DIR__ . '/../views/ingresos/pdf_ingresos.php';
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('ingresos.pdf', ['Attachment' => true]);
        exit;
    }
} 