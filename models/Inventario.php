<?php
require_once __DIR__ . '/../core/Database.php';

class Inventario {
    public static function obtenerTodos($id_sucursal = null) {
        $conn = Database::getConexion();
        $where = '';
        $params = [];
        if ($id_sucursal) {
            $where = 'WHERE isuc.id_sucursal = $1';
            $params[] = $id_sucursal;
        }
        $sql = "SELECT isuc.*, p.nombre_producto, p.talla_producto, s.nombre_sucursal
                FROM inventario_sucursal isuc
                JOIN producto p ON isuc.id_producto = p.id_producto
                JOIN sucursal s ON isuc.id_sucursal = s.id_sucursal
                $where
                ORDER BY p.nombre_producto ASC";
        $result = $params ? pg_query_params($conn, $sql, $params) : pg_query($conn, $sql);
        $inventario = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $inventario[] = $row;
            }
        }
        return $inventario;
    }

    public static function obtenerSucursalesActivas() {
        $conn = Database::getConexion();
        $sql = "SELECT id_sucursal, nombre_sucursal FROM sucursal WHERE estado_sucursal = true ORDER BY nombre_sucursal ASC";
        $result = pg_query($conn, $sql);
        $sucursales = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $sucursales[] = $row;
            }
        }
        return $sucursales;
    }
} 