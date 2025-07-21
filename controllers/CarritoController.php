<?php
require_once __DIR__ . '/../models/Carrito.php';
class CarritoController {
    public function listar() {
        require __DIR__ . '/../views/carrito/listar.php';
    }
    public function datos() {
        header('Content-Type: application/json');
        $carrito_id = Carrito::obtenerCarritoId();
        $items = Carrito::obtenerItems($carrito_id);
        $totales = Carrito::calcularTotales($items);
        $items_front = [];
        foreach ($items as $item) {
            $precioOriginal = $item['precio_venta'];
            $oferta = $item['oferta'];
            $precioConDescuento = $precioOriginal * (1 - ($oferta / 100));
            $items_front[] = [
                'id' => $item['id'],
                'nombre_producto' => $item['nombre_producto'],
                'url_imagen' => $item['url_imagen'],
                'talla' => $item['talla'],
                'cantidad' => $item['cantidad'],
                'precio_venta' => $precioOriginal,
                'precio_con_descuento' => $precioConDescuento,
                'oferta' => $oferta
            ];
        }
        echo json_encode([
            'items' => $items_front,
            'total' => $totales['total'],
            'totalOriginal' => $totales['totalOriginal'],
            'totalDescuento' => $totales['totalDescuento'],
            'cantidadTotal' => $totales['cantidadTotal']
        ]);
        exit;
    }
    public function eliminar() {
        header('Content-Type: application/json');
        $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
        if ($item_id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }
        $result = Carrito::eliminarItem($item_id);
        echo json_encode($result);
        exit;
    }
    public function actualizar() {
        header('Content-Type: application/json');
        $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
        $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
        if ($item_id <= 0 || $cantidad < 1) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            exit;
        }
        $result = Carrito::actualizarCantidad($item_id, $cantidad);
        echo json_encode($result);
        exit;
    }
    public function contador() {
        header('Content-Type: application/json');
        $carrito_id = Carrito::obtenerCarritoId();
        $total_items = 0;
        if ($carrito_id) {
            $total_items = Carrito::contarItems($carrito_id);
        }
        echo json_encode(['total' => $total_items]);
        exit;
    }
    public function registrar() {
        header('Content-Type: application/json');
        try {
            $catalogo_id = isset($_POST['catalogo_id']) ? intval($_POST['catalogo_id']) : 0;
            $talla = isset($_POST['talla']) ? trim($_POST['talla']) : '';
            $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
            $result = Carrito::registrarItem($catalogo_id, $talla, $cantidad);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Excepción: ' . $e->getMessage()]);
        }
        exit;
    }
} 