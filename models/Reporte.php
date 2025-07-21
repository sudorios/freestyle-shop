<?php
require_once __DIR__ . '/../core/Database.php';

class Reporte {
    public static function getTotalProductos() {
        $conn = Database::getConexion();
        $sql = "SELECT COUNT(*) FROM producto";
        return (int)pg_fetch_result(pg_query($conn, $sql), 0, 0);
    }
    public static function getTotalPedidos() {
        $conn = Database::getConexion();
        $sql = "SELECT COUNT(*) FROM pedido";
        return (int)pg_fetch_result(pg_query($conn, $sql), 0, 0);
    }
    public static function getTotalIngresos() {
        $conn = Database::getConexion();
        $sql = "SELECT COUNT(*) FROM ingreso";
        return (int)pg_fetch_result(pg_query($conn, $sql), 0, 0);
    }
    public static function getTotalUsuarios() {
        $conn = Database::getConexion();
        $sql = "SELECT COUNT(*) FROM usuario";
        return (int)pg_fetch_result(pg_query($conn, $sql), 0, 0);
    }
    public static function getProductosPorEstado() {
        $conn = Database::getConexion();
        $sql = "SELECT estado, COUNT(*) as cantidad FROM inventario_sucursal GROUP BY estado ORDER BY estado";
        $res = pg_query($conn, $sql);
        $estados = [];
        $cantidades = [];
        while ($row = pg_fetch_assoc($res)) {
            $estados[] = strtoupper($row['estado']);
            $cantidades[] = (int)$row['cantidad'];
        }
        return [$estados, $cantidades];
    }
    public static function getPedidosPorMes() {
        $conn = Database::getConexion();
        $sql = "SELECT TO_CHAR(fecha, 'YYYY-MM') AS mes, COUNT(*) AS cantidad FROM pedido GROUP BY mes ORDER BY mes";
        $res = pg_query($conn, $sql);
        $meses = [];
        $cant_pedidos = [];
        while ($row = pg_fetch_assoc($res)) {
            $meses[] = $row['mes'];
            $cant_pedidos[] = (int)$row['cantidad'];
        }
        return [$meses, $cant_pedidos];
    }
    public static function getIngresosPorMes() {
        $conn = Database::getConexion();
        $sql = "SELECT TO_CHAR(fecha_ingreso, 'YYYY-MM') AS mes, COUNT(*) AS cantidad FROM ingreso GROUP BY mes ORDER BY mes";
        $res = pg_query($conn, $sql);
        $meses = [];
        $cant_ingresos = [];
        while ($row = pg_fetch_assoc($res)) {
            $meses[] = $row['mes'];
            $cant_ingresos[] = (int)$row['cantidad'];
        }
        return [$meses, $cant_ingresos];
    }
    public static function getTopProductosVendidos($limit = 5) {
        $conn = Database::getConexion();
        $sql = "SELECT p.nombre_producto, SUM(dp.cantidad) as total_vendidos
            FROM detalle_pedido dp
            JOIN producto p ON dp.id_producto = p.id_producto
            GROUP BY p.nombre_producto
            ORDER BY total_vendidos DESC
            LIMIT $limit";
        $res = pg_query($conn, $sql);
        $top_productos = [];
        $top_cantidades = [];
        if ($res) {
            while ($row = pg_fetch_assoc($res)) {
                $top_productos[] = $row['nombre_producto'];
                $top_cantidades[] = (int)$row['total_vendidos'];
            }
        }
        return [$top_productos, $top_cantidades];
    }
} 