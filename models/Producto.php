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

    public static function eliminar($id_producto) {
        $conn = Database::getConexion();
        $sql = "UPDATE producto SET estado = false WHERE id_producto = $1";
        $result = pg_query_params($conn, $sql, [$id_producto]);
        return $result;
    }

    public static function subirImagen($id_producto, $url_imagen, $vista_producto) {
        $conn = Database::getConexion();
        $sql = "INSERT INTO imagenes_producto (producto_id, url_imagen, creado_en, actualizado_en, vista_producto) VALUES ($1, $2, NOW(), NOW(), $3)";
        $params = [$id_producto, $url_imagen, $vista_producto];
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function obtenerImagenes($id_producto) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM imagenes_producto WHERE producto_id = $1 ORDER BY creado_en DESC";
        $result = pg_query_params($conn, $sql, array($id_producto));
        $imagenes = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $imagenes[] = $row;
            }
        }
        return $imagenes;
    }

    public static function obtenerPorId($id_producto) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM producto WHERE id_producto = $1";
        $result = pg_query_params($conn, $sql, [$id_producto]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    public static function generarReferencia() {
        $conn = Database::getConexion();
        for ($i = 0; $i < 5; $i++) {
            $ref = strval(rand(10000000, 99999999));
            $result = pg_query_params($conn, "SELECT 1 FROM producto WHERE ref_producto = $1", [$ref]);
            if (pg_num_rows($result) === 0) {
                return $ref;
            }
        }
        return false;
    }
} 