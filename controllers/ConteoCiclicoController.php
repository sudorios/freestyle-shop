<?php
require_once __DIR__ . '/../models/ConteoCiclico.php';

class ConteoCiclicoController {
    public function listar() {
        $producto_id = $_GET['id_producto'] ?? '';
        $sucursal_id = $_GET['id_sucursal'] ?? '';
        $fecha_desde = $_GET['fecha_desde'] ?? '';
        $fecha_hasta = $_GET['fecha_hasta'] ?? '';
        $usuario = $_GET['usuario'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $where = [];
        if ($producto_id && $sucursal_id) {
            $where[] = "c.producto_id = '" . pg_escape_string(Database::getConexion(), $producto_id) . "'";
            $where[] = "c.sucursal_id = '" . pg_escape_string(Database::getConexion(), $sucursal_id) . "'";
        }
        if ($fecha_desde) {
            $where[] = "c.fecha_conteo >= '" . pg_escape_string(Database::getConexion(), $fecha_desde) . "'";
        }
        if ($fecha_hasta) {
            $where[] = "c.fecha_conteo <= '" . pg_escape_string(Database::getConexion(), $fecha_hasta) . "'";
        }
        if ($usuario) {
            $usuario_esc = pg_escape_string(Database::getConexion(), $usuario);
            $where[] = "(u.nombre_usuario ILIKE '%$usuario_esc%' OR CAST(c.usuario_id AS TEXT) ILIKE '%$usuario_esc%')";
        }
        if ($estado) {
            $where[] = "c.estado_conteo = '" . pg_escape_string(Database::getConexion(), $estado) . "'";
        }
        $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $nombre_producto = '';
        $nombre_sucursal = '';
        if ($producto_id) {
            $nombre_producto = ConteoCiclico::obtenerNombreProducto($producto_id);
        }
        if ($sucursal_id) {
            $nombre_sucursal = ConteoCiclico::obtenerNombreSucursal($sucursal_id);
        }
        $cantidad_sistema = '';
        if ($producto_id && $sucursal_id) {
            $cantidad_sistema = ConteoCiclico::obtenerCantidadSistema($producto_id, $sucursal_id);
        }
        $conteos = ConteoCiclico::obtenerTodos($where_sql);
        require __DIR__ . '/../views/conteos_ciclicos/listar.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=conteociclico&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        session_start();
        $data = [
            'producto_id' => $_POST['id_producto'] ?? '',
            'sucursal_id' => $_POST['id_sucursal'] ?? '',
            'cantidad_real' => $_POST['cantidad_real'] ?? '',
            'cantidad_sistema' => $_POST['cantidad_sistema'] ?? '',
            'diferencia' => ($_POST['cantidad_real'] ?? 0) - ($_POST['cantidad_sistema'] ?? 0),
            'fecha_conteo' => $_POST['fecha_conteo'] ?? date('Y-m-d'),
            'usuario_id' => $_SESSION['id'] ?? null,
            'comentarios' => $_POST['comentarios'] ?? '',
            'estado_conteo' => $_POST['estado_conteo'] ?? 'Pendiente'
        ];
        $ok = ConteoCiclico::registrar($data);
        if ($ok) {
            header('Location: index.php?controller=conteociclico&action=listar&id_producto=' . $data['producto_id'] . '&id_sucursal=' . $data['sucursal_id'] . '&success=2');
        } else {
            $msg = urlencode(pg_last_error());
            header('Location: index.php?controller=conteociclico&action=listar&id_producto=' . $data['producto_id'] . '&id_sucursal=' . $data['sucursal_id'] . '&error=1&msg=' . $msg);
        }
        exit();
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=conteociclico&action=listar&error=2&msg=Acceso denegado');
            exit;
        }
        session_start();
        $fecha_ajuste = date('Y-m-d');
        $data = [
            'id_conteo' => $_POST['id_conteo'] ?? '',
            'producto_id' => $_POST['id_producto'] ?? '',
            'sucursal_id' => $_POST['id_sucursal'] ?? '',
            'cantidad_real' => $_POST['cantidad_real'] ?? '',
            'cantidad_sistema' => $_POST['cantidad_sistema'] ?? '',
            'diferencia' => ($_POST['cantidad_real'] ?? 0) - ($_POST['cantidad_sistema'] ?? 0),
            'fecha_conteo' => $_POST['fecha_conteo'] ?? date('Y-m-d'),
            'usuario_id' => $_SESSION['id'] ?? null,
            'comentarios' => $_POST['comentarios'] ?? '',
            'estado_conteo' => $_POST['estado_conteo'] ?? 'Pendiente',
            'fecha_ajuste' => $fecha_ajuste
        ];
        $ok = ConteoCiclico::actualizar($data);
        if ($ok === true) {
            header('Location: index.php?controller=conteociclico&action=listar&id_producto=' . $data['producto_id'] . '&id_sucursal=' . $data['sucursal_id'] . '&success=2');
        } else {
            $msg = urlencode($ok);
            header('Location: index.php?controller=conteociclico&action=listar&id_producto=' . $data['producto_id'] . '&id_sucursal=' . $data['sucursal_id'] . '&error=1&msg=' . $msg);
        }
        exit();
    }

    public function exportarCSV() {
        $producto_id = $_GET['id_producto'] ?? '';
        $sucursal_id = $_GET['id_sucursal'] ?? '';
        $where_sql = '';
        if ($producto_id && $sucursal_id) {
            $where_sql = "WHERE c.producto_id = '" . pg_escape_string(Database::getConexion(), $producto_id) . "' AND c.sucursal_id = '" . pg_escape_string(Database::getConexion(), $sucursal_id) . "'";
        }
        $conteos = ConteoCiclico::obtenerTodos($where_sql);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=conteos_ciclicos.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Cantidad Real', 'Cantidad Sistema', 'Diferencia', 'Fecha Conteo', 'Usuario', 'Estado', 'Fecha Ajuste', 'Comentarios']);
        foreach ($conteos as $row) {
            fputcsv($output, [
                $row['id_conteo'],
                $row['cantidad_real'],
                $row['cantidad_sistema'],
                $row['diferencia'],
                $row['fecha_conteo'],
                $row['nombre_usuario'] ?? $row['usuario_id'],
                $row['estado_conteo'],
                $row['fecha_ajuste'],
                $row['comentarios']
            ]);
        }
        fclose($output);
        exit;
    }

    public function exportarPDF() {
        require_once __DIR__ . '/../vendor/autoload.php';
        $producto_id = $_GET['id_producto'] ?? '';
        $sucursal_id = $_GET['id_sucursal'] ?? '';
        $where_sql = '';
        if ($producto_id && $sucursal_id) {
            $where_sql = "WHERE c.producto_id = '" . pg_escape_string(Database::getConexion(), $producto_id) . "' AND c.sucursal_id = '" . pg_escape_string(Database::getConexion(), $sucursal_id) . "'";
        }
        $conteos = ConteoCiclico::obtenerTodos($where_sql);
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        ob_start();
        require __DIR__ . '/../views/conteos_ciclicos/pdf_conteos.php';
        $html = ob_get_clean();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('conteos_ciclicos.pdf', ['Attachment' => true]);
        exit;
    }
} 