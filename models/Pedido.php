<?php
require_once __DIR__ . '/../core/Database.php';

class Pedido {
    public static function obtenerTodos($filtros = []) {
        $conn = Database::getConexion();
        $where = [];
        $params = [];
        $i = 1;
        if (!empty($filtros['fecha_desde'])) {
            $where[] = "p.fecha >= $" . $i;
            $params[] = $filtros['fecha_desde'];
            $i++;
        }
        if (!empty($filtros['fecha_hasta'])) {
            $where[] = "p.fecha <= $" . $i;
            $params[] = $filtros['fecha_hasta'];
            $i++;
        }
        if (!empty($filtros['estado'])) {
            $where[] = "p.estado = $" . $i;
            $params[] = $filtros['estado'];
            $i++;
        }
        $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $order_sql = 'ORDER BY p.fecha DESC';
        if (!empty($filtros['orden_precio'])) {
            if ($filtros['orden_precio'] === 'mayor') {
                $order_sql = 'ORDER BY p.total DESC';
            } elseif ($filtros['orden_precio'] === 'menor') {
                $order_sql = 'ORDER BY p.total ASC';
            }
        }
        $sql = "SELECT p.id_pedido, u.nombre_usuario, p.fecha, p.total, p.estado
                FROM pedido p
                LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
                $where_sql
                $order_sql";
        $result = pg_query_params($conn, $sql, $params);
        $pedidos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $pedidos[] = $row;
            }
        }
        return $pedidos;
    }

    public static function cambiarEstado($id_pedido, $nuevo_estado) {
        $conn = Database::getConexion();
        $sql = "UPDATE pedido SET estado = $1 WHERE id_pedido = $2";
        $result = pg_query_params($conn, $sql, [$nuevo_estado, $id_pedido]);
        return $result;
    }

    public static function cancelar($id_pedido) {
        $conn = Database::getConexion();
        $sql = "UPDATE pedido SET estado = 'CANCELADO' WHERE id_pedido = $1";
        $result = pg_query_params($conn, $sql, [$id_pedido]);
        return $result;
    }

    public static function obtenerPorId($id_pedido) {
        $conn = Database::getConexion();
        $sql = "SELECT p.*, u.nombre_usuario, u.email_usuario
                FROM pedido p
                LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE p.id_pedido = $1";
        $result = pg_query_params($conn, $sql, [$id_pedido]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    public static function obtenerDetalles($id_pedido) {
        $conn = Database::getConexion();
        $sql = "SELECT dp.*, p.nombre_producto, p.talla_producto
                FROM detalle_pedido dp
                JOIN producto p ON dp.id_producto = p.id_producto
                WHERE dp.id_pedido = $1";
        $result = pg_query_params($conn, $sql, [$id_pedido]);
        $detalles = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $detalles[] = $row;
            }
        }
        return $detalles;
    }

    public static function registrar($id_usuario, $productos, $direccion_envio = '', $metodo_pago = '', $observaciones = '', $total = 0) {
        $conn = Database::getConexion();
        pg_query($conn, 'BEGIN');
        $sql = "INSERT INTO pedido (id_usuario, fecha, total, estado, direccion_envio, metodo_pago, observaciones) VALUES ($1, NOW(), $2, 'PENDIENTE', $3, $4, $5) RETURNING id_pedido";
        $result = pg_query_params($conn, $sql, [$id_usuario, $total, $direccion_envio, $metodo_pago, $observaciones]);
        if (!$result) {
            pg_query($conn, 'ROLLBACK');
            return false;
        }
        $row = pg_fetch_assoc($result);
        $id_pedido = $row['id_pedido'];
        $total_calculado = 0;
        foreach ($productos as $prod) {
            $sql_det = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES ($1, $2, $3, $4)";
            $ok = pg_query_params($conn, $sql_det, [$id_pedido, $prod['id_producto'], $prod['cantidad'], $prod['precio_unitario']]);
            if (!$ok) {
                pg_query($conn, 'ROLLBACK');
                return false;
            }
            $total_calculado += $prod['cantidad'] * $prod['precio_unitario'];
        }
        $sql_upd = "UPDATE pedido SET total = $1 WHERE id_pedido = $2";
        pg_query_params($conn, $sql_upd, [$total_calculado, $id_pedido]);
        pg_query($conn, 'COMMIT');
        return $id_pedido;
    }
} 