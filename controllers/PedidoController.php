<?php
require_once __DIR__ . '/../models/Pedido.php';

class PedidoController {
    public function listar() {
        $filtros = [
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'orden_precio' => $_GET['orden_precio'] ?? ''
        ];
        $pedidos = Pedido::obtenerTodos($filtros);
        require __DIR__ . '/../views/pedidos/listar.php';
    }

    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=pedido&action=listar&error=Acceso denegado');
            exit;
        }
        $id_pedido = $_POST['id_pedido'] ?? null;
        $nuevo_estado = $_POST['nuevo_estado'] ?? null;
        if ($id_pedido && $nuevo_estado) {
            $ok = Pedido::cambiarEstado($id_pedido, $nuevo_estado);
            if ($ok) {
                header('Location: index.php?controller=pedido&action=listar&success=2');
            } else {
                header('Location: index.php?controller=pedido&action=listar&error=Error al cambiar estado');
            }
        } else {
            header('Location: index.php?controller=pedido&action=listar&error=Datos incompletos');
        }
        exit;
    }

    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=pedido&action=listar&error=Acceso denegado');
            exit;
        }
        $id_pedido = $_POST['id_pedido'] ?? null;
        if ($id_pedido) {
            $ok = Pedido::cancelar($id_pedido);
            if ($ok) {
                header('Location: index.php?controller=pedido&action=listar&success=2');
            } else {
                header('Location: index.php?controller=pedido&action=listar&error=Error al cancelar pedido');
            }
        } else {
            header('Location: index.php?controller=pedido&action=listar&error=Datos incompletos');
        }
        exit;
    }

    public function ver() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=pedido&action=listar&error=ID no especificado');
            exit;
        }
        $pedido = Pedido::obtenerPorId($id);
        $detalles = Pedido::obtenerDetalles($id);
        require __DIR__ . '/../views/pedidos/ver.php';
    }

    public function exportarPDF() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=pedido&action=listar&error=ID no especificado');
            exit;
        }
        $pedido = Pedido::obtenerPorId($id);
        $detalles = Pedido::obtenerDetalles($id);
        require_once __DIR__ . '/../vendor/autoload.php';
        ob_start();
        require __DIR__ . '/../views/pedidos/pdf_pedido.php';
        $html = ob_get_clean();
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('pedido_' . $id . '.pdf', ['Attachment' => false]);
        exit;
    }

    public function exportarPDFTodos() {
        $filtros = [
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'orden_precio' => $_GET['orden_precio'] ?? ''
        ];
        $pedidos = Pedido::obtenerTodos($filtros);
        require_once __DIR__ . '/../vendor/autoload.php';
        ob_start();
        require __DIR__ . '/../views/pedidos/pdf_pedidos.php';
        $html = ob_get_clean();
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('pedidos.pdf', ['Attachment' => false]);
        exit;
    }

    public function generarBoletaPDF() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=pedido&action=listar&error=ID no especificado');
            exit;
        }
        $pedido = Pedido::obtenerPorId($id);
        $detalles = Pedido::obtenerDetalles($id);
        require_once __DIR__ . '/../vendor/autoload.php';
        ob_start();
        require __DIR__ . '/../views/pedidos/boleta_pdf.php';
        $html = ob_get_clean();
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('boleta_pedido_' . $id . '.pdf', ['Attachment' => false]);
        exit;
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_POST['id_usuario'] ?? null;
            $productos = $_POST['productos'] ?? [];
            if ($id_usuario && !empty($productos)) {
                $id_pedido = Pedido::registrar($id_usuario, $productos);
                if ($id_pedido) {
                    header('Location: index.php?controller=pedido&action=ver&id=' . $id_pedido . '&success=1');
                    exit;
                } else {
                    $error = 'Error al registrar el pedido';
                }
            } else {
                $error = 'Datos incompletos';
            }
        }
        $usuarios = $this->obtenerUsuariosClientes();
        $productos = $this->obtenerProductos();
        require __DIR__ . '/../views/pedidos/registrar.php';
    }

    public function checkout() {
        require_once __DIR__ . '/../utils/queries.php';
        $mostrar_formulario = true;
        $msg = null;
        $success = false;
        $error = false;
        $carrito = [];
        $total = 0;
        $envio = 0;
        $total_final = 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['id'])) {
                header('Location: index.php?controller=usuario&action=login&error=2&msg=Debes iniciar sesión para finalizar tu compra');
                exit();
            }
            $id_usuario = $_SESSION['id'] ?? null;
            $direccion_envio = $_POST['direccion_envio'] ?? '';
            $metodo_pago = $_POST['metodo_pago'] ?? '';
            $observaciones = $_POST['observaciones'] ?? '';
            $productos = [];
            if (!empty($_POST['productos'])) {
                foreach ($_POST['productos'] as $json) {
                    $productos[] = json_decode($json, true);
                }
            }
            $total = floatval($_POST['total'] ?? 0);
            if ($id_usuario && $direccion_envio && $metodo_pago && !empty($productos)) {
                $id_pedido = Pedido::registrar($id_usuario, $productos, $direccion_envio, $metodo_pago, $observaciones, $total);
                if ($id_pedido) {
                    $success = true;
                    $msg = '¡Pedido registrado correctamente!';
                    $mostrar_formulario = false;
                } else {
                    $error = true;
                    $msg = 'Ocurrió un error al registrar el pedido.';
                }
            } else {
                $error = true;
                $msg = 'Datos incompletos.';
                $debug_log = 'DEBUG CHECKOUT: id_usuario=' . var_export($id_usuario, true) . ' direccion_envio=' . var_export($direccion_envio, true) . ' metodo_pago=' . var_export($metodo_pago, true) . ' productos=' . var_export($productos, true) . ' total=' . var_export($total, true);
            }
        } else {
            $ids = isset($_GET['items']) ? explode(',', $_GET['items']) : [];
            $ids = array_filter(array_map('intval', $ids));
            if (!empty($ids)) {
                $placeholders = implode(',', array_map(function($i) { static $c=1; return '$'.($c++); }, $ids));
                $sql = getCarritoItemsByIdsQuery($placeholders);
                $conn = Database::getConexion();
                $res = $ids ? pg_query_params($conn, $sql, $ids) : false;
                if ($res) {
                    while ($item = pg_fetch_assoc($res)) {
                        $carrito[] = $item;
                        $total += $item['cantidad'] * $item['precio_unitario'];
                    }
                }
            }
            $envio = $total >= 99 ? 0 : 15;
            $total_final = $total + $envio;
            if (empty($carrito)) {
                $mostrar_formulario = false;
                $error = true;
                $msg = 'No hay productos seleccionados para el checkout.';
            }
        }
        require __DIR__ . '/../views/pedidos/checkout.php';
    }

    private function obtenerUsuariosClientes() {
        $conn = Database::getConexion();
        $sql = "SELECT id_usuario, nombre_usuario FROM usuario WHERE rol_usuario = 'cliente' AND estado_usuario = true ORDER BY nombre_usuario ASC";
        $result = pg_query($conn, $sql);
        $usuarios = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }

    private function obtenerProductos() {
        $conn = Database::getConexion();
        $sql = "SELECT id_producto, nombre_producto, precio_venta FROM producto WHERE estado_producto = true ORDER BY nombre_producto ASC";
        $result = pg_query($conn, $sql);
        $productos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $productos[] = $row;
            }
        }
        return $productos;
    }
} 