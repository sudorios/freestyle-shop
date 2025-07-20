<?php
require_once __DIR__ . '/../core/Database.php';

class Categoria {
    public static function obtenerTodas() {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM categoria WHERE estado_categoria = true ORDER BY id_categoria DESC";
        $result = pg_query($conn, $sql);
        $categorias = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $categorias[] = $row;
            }
        }
        return $categorias;
    }

    public static function obtenerPorId($id_categoria) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM categoria WHERE id_categoria = $1";
        $result = pg_query_params($conn, $sql, [$id_categoria]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    public static function registrar($nombre, $descripcion) {
        $conn = Database::getConexion();
        $sql = "INSERT INTO categoria(nombre_categoria, descripcion_categoria, estado_categoria, creado_en) VALUES ($1, $2, $3, $4)";
        $params = [$nombre, $descripcion, true, date('Y-m-d H:i:s')];
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function actualizar($id_categoria, $nombre, $descripcion, $estado) {
        $conn = Database::getConexion();
        $sql = "UPDATE categoria SET nombre_categoria = $1, descripcion_categoria = $2, estado_categoria = $3 WHERE id_categoria = $4";
        $params = [$nombre, $descripcion, $estado, $id_categoria];
        $result = pg_query_params($conn, $sql, $params);
        
        return $result;
    }

    public static function eliminar($id_categoria) {
        $conn = Database::getConexion();
        $sql = "UPDATE categoria SET estado_categoria = false WHERE id_categoria = $1";
        $result = pg_query_params($conn, $sql, [$id_categoria]);
        return $result;
    }

    public static function existePorNombre($nombre, $excluir_id = null) {
        $conn = Database::getConexion();
        if ($excluir_id) {
            $sql = "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1 AND id_categoria != $2";
            $result = pg_query_params($conn, $sql, [$nombre, $excluir_id]);
        } else {
            $sql = "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1";
            $result = pg_query_params($conn, $sql, [$nombre]);
        }
        return pg_num_rows($result) > 0;
    }
} 