<?php
require_once __DIR__ . '/../core/Database.php';

class Subcategoria {
    public static function obtenerTodas() {
        $conn = Database::getConexion();
        $sql = "SELECT s.*, c.nombre FROM subcategoria s JOIN categoria c ON s.categoria_id = c.categoria_id WHERE s.estado = true ORDER BY s.id_subcategoria DESC";
        $result = pg_query($conn, $sql);
        $subcategorias = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $subcategorias[] = $row;
            }
        }
        return $subcategorias;
    }

    public static function obtenerPorId($id_subcategoria) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM subcategoria WHERE id_subcategoria = $1";
        $result = pg_query_params($conn, $sql, [$id_subcategoria]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    public static function registrar($nombre, $descripcion, $categoria_id) {
        $conn = Database::getConexion();
        $sql = "INSERT INTO subcategoria (nombre_subcategoria, descripcion_subcategoria, categoria_id, estado) VALUES ($1, $2, $3, $4)";
        $params = [$nombre, $descripcion, $categoria_id, true];
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function actualizar($id_subcategoria, $nombre, $descripcion, $categoria_id) {
        $conn = Database::getConexion();
        $sql = "UPDATE subcategoria SET nombre_subcategoria = $1, descripcion_subcategoria = $2, categoria_id = $3, actualizado_en = CURRENT_TIMESTAMP WHERE id_subcategoria = $4";
        $params = [$nombre, $descripcion, $categoria_id, $id_subcategoria];
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function eliminar($id_subcategoria) {
        $conn = Database::getConexion();
        $sql = "UPDATE subcategoria SET estado = false WHERE id_subcategoria = $1";
        $result = pg_query_params($conn, $sql, [$id_subcategoria]);
        return $result;
    }

    public static function existePorNombre($nombre, $excluir_id = null) {
        $conn = Database::getConexion();
        if ($excluir_id) {
            $sql = "SELECT id_subcategoria FROM subcategoria WHERE nombre_subcategoria = $1 AND id_subcategoria != $2";
            $result = pg_query_params($conn, $sql, [$nombre, $excluir_id]);
        } else {
            $sql = "SELECT id_subcategoria FROM subcategoria WHERE nombre_subcategoria = $1";
            $result = pg_query_params($conn, $sql, [$nombre]);
        }
        return pg_num_rows($result) > 0;
    }

    public static function obtenerPorCategoria($categoria_id) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM subcategoria WHERE categoria_id = $1 AND estado = true ORDER BY nombre_subcategoria ASC";
        $result = pg_query_params($conn, $sql, [$categoria_id]);
        $subcategorias = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $subcategorias[] = $row;
            }
        }
        return $subcategorias;
    }
} 