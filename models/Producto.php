<?php
require_once __DIR__ . '/../core/Database.php';

class Producto {
    public static function obtenerTodos() {
        $conn = Database::getConexion();
        $sql = "SELECT p.*, s.nombre_subcategoria FROM producto p LEFT JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria WHERE p.estado = true ORDER BY p.id_producto DESC";
        $result = pg_query($conn, $sql);
        $productos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $productos[] = $row;
            }
        }
        return $productos;
    }

    public static function obtenerSubcategorias() {
        $conn = Database::getConexion();
        $sql = "SELECT id_subcategoria, nombre_subcategoria FROM subcategoria WHERE estado = true ORDER BY nombre_subcategoria ASC";
        $result = pg_query($conn, $sql);
        $subcategorias = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $subcategorias[] = $row;
            }
        }
        return $subcategorias;
    }

} 