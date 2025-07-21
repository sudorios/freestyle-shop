<?php
require_once __DIR__ . '/../core/Database.php';
class Carrito {
    public static function obtenerCarritoId() {
        $conn = Database::getConexion();
        $usuario_id = $_SESSION['id'] ?? null;
        $session_id = session_id();
        if ($usuario_id) {
            $sql = "SELECT id FROM carrito WHERE usuario_id = $1";
            $params = [$usuario_id];
        } else {
            $sql = "SELECT id FROM carrito WHERE session_id = $1";
            $params = [$session_id];
        }
        $result = pg_query_params($conn, $sql, $params);
        $row = pg_fetch_assoc($result);
        return $row ? $row['id'] : null;
    }
    public static function obtenerItems($carrito_id) {
        $conn = Database::getConexion();
        $items = [];
        if ($carrito_id) {
            $sql = "SELECT ci.id, ci.cantidad, ci.talla, ci.precio_unitario, p.nombre_producto, ip.url_imagen, i.precio_venta, cp.oferta
                FROM carrito_items ci
                JOIN producto p ON ci.producto_id = p.id_producto
                JOIN catalogo_productos cp ON cp.producto_id = p.id_producto
                JOIN ingreso i ON cp.ingreso_id = i.id
                LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
                WHERE ci.carrito_id = $1 AND ci.estado = 'activo'";
            $res = pg_query_params($conn, $sql, [$carrito_id]);
            while ($item = pg_fetch_assoc($res)) {
                $items[] = $item;
            }
        }
        return $items;
    }
    public static function obtenerItemsPorIds($ids) {
        $conn = Database::getConexion();
        if (empty($ids)) return [];
        $placeholders = implode(',', array_map(function($i) { static $c=1; return '$'.($c++); }, $ids));
        $sql = "SELECT ci.id, ci.producto_id, ci.cantidad, ci.precio_unitario, ci.talla, p.nombre_producto, ip.url_imagen
            FROM carrito_items ci
            JOIN producto p ON ci.producto_id = p.id_producto
            LEFT JOIN catalogo_productos cp ON cp.producto_id = p.id_producto
            LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
            WHERE ci.id IN ($placeholders) AND ci.estado = 'activo'";
        $res = pg_query_params($conn, $sql, $ids);
        $items = [];
        if ($res) {
            while ($item = pg_fetch_assoc($res)) {
                $items[] = $item;
            }
        }
        return $items;
    }
    public static function calcularTotales($items) {
        $total = 0;
        $totalOriginal = 0;
        $totalDescuento = 0;
        $cantidadTotal = 0;
        foreach ($items as $item) {
            $precioOriginal = $item['precio_venta'];
            $oferta = $item['oferta'];
            $precioConDescuento = $precioOriginal * (1 - ($oferta / 100));
            $subtotal = $precioConDescuento * $item['cantidad'];
            $subtotalOriginal = $precioOriginal * $item['cantidad'];
            $descuento = $subtotalOriginal - $subtotal;
            $cantidadTotal += $item['cantidad'];
            $total += $subtotal;
            $totalOriginal += $subtotalOriginal;
            $totalDescuento += $descuento;
        }
        return [
            'total' => $total,
            'totalOriginal' => $totalOriginal,
            'totalDescuento' => $totalDescuento,
            'cantidadTotal' => $cantidadTotal
        ];
    }
    public static function eliminarItem($item_id) {
        $conn = Database::getConexion();
        $carrito_id = self::obtenerCarritoId();
        if (!$carrito_id) return ['success' => false, 'error' => 'Carrito no encontrado'];
        $sql = "SELECT id FROM carrito_items WHERE id = $1 AND carrito_id = $2 AND estado = 'activo'";
        $res = pg_query_params($conn, $sql, [$item_id, $carrito_id]);
        if (!pg_fetch_assoc($res)) {
            return ['success' => false, 'error' => 'Item no encontrado en tu carrito'];
        }
        $sql = "UPDATE carrito_items SET estado = 'eliminado' WHERE id = $1";
        pg_query_params($conn, $sql, [$item_id]);
        return ['success' => true];
    }
    public static function actualizarCantidad($item_id, $cantidad) {
        $conn = Database::getConexion();
        $carrito_id = self::obtenerCarritoId();
        if (!$carrito_id) return ['success' => false, 'error' => 'Carrito no encontrado'];
        $sql = "SELECT id FROM carrito_items WHERE id = $1 AND carrito_id = $2 AND estado = 'activo'";
        $res = pg_query_params($conn, $sql, [$item_id, $carrito_id]);
        if (!pg_fetch_assoc($res)) {
            return ['success' => false, 'error' => 'Item no encontrado en tu carrito'];
        }
        $sql = "UPDATE carrito_items SET cantidad = $1 WHERE id = $2";
        pg_query_params($conn, $sql, [$cantidad, $item_id]);
        return ['success' => true];
    }
    public static function contarItems($carrito_id) {
        $conn = Database::getConexion();
        $sql = "SELECT SUM(cantidad) AS total FROM carrito_items WHERE carrito_id = $1 AND estado = 'activo'";
        $res = pg_query_params($conn, $sql, [$carrito_id]);
        $row = pg_fetch_assoc($res);
        return $row && $row['total'] ? (int)$row['total'] : 0;
    }
    public static function registrarItem($catalogo_id, $talla, $cantidad) {
        $conn = Database::getConexion();
        if ($catalogo_id <= 0 || empty($talla) || $cantidad < 1) {
            return ['success' => false, 'error' => 'Datos inválidos'];
        }
        $sql = "SELECT cp.producto_id, i.precio_venta FROM catalogo_productos cp JOIN ingreso i ON cp.ingreso_id = i.id WHERE cp.id = $1 LIMIT 1";
        $res = pg_query_params($conn, $sql, [$catalogo_id]);
        $prod = pg_fetch_assoc($res);
        if (!$prod) {
            return ['success' => false, 'error' => 'Producto no encontrado'];
        }
        $producto_id = $prod['producto_id'];
        $precio_unitario = $prod['precio_venta'];
        $carrito_id = self::obtenerCarritoId();
        if (!$carrito_id) {
            $usuario_id = $_SESSION['id'] ?? null;
            $session_id = session_id();
            if ($usuario_id) {
                $sql = "INSERT INTO carrito (usuario_id) VALUES ($1) RETURNING id";
                $params = [$usuario_id];
            } else {
                $sql = "INSERT INTO carrito (session_id) VALUES ($1) RETURNING id";
                $params = [$session_id];
            }
            $result = pg_query_params($conn, $sql, $params);
            $carrito_id = pg_fetch_result($result, 0, 'id');
        }
        $sql = "SELECT id, cantidad FROM carrito_items WHERE carrito_id = $1 AND producto_id = $2 AND talla = $3 AND estado = 'activo'";
        $params = [$carrito_id, $producto_id, $talla];
        $result = pg_query_params($conn, $sql, $params);
        $row = pg_fetch_assoc($result);
        if ($row) {
            $nuevo_total = $row['cantidad'] + $cantidad;
            $sql = "UPDATE carrito_items SET cantidad = $1 WHERE id = $2";
            pg_query_params($conn, $sql, [$nuevo_total, $row['id']]);
        } else {
            $sql = "INSERT INTO carrito_items (carrito_id, producto_id, talla, cantidad, precio_unitario) VALUES ($1, $2, $3, $4, $5)";
            pg_query_params($conn, $sql, [$carrito_id, $producto_id, $talla, $cantidad, $precio_unitario]);
        }
        return ['success' => true];
    }
} 