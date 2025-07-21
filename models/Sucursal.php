<?php
require_once __DIR__ . '/../core/Database.php';

class Sucursal {
    public static function obtenerTodas() {
        $conn = Database::getConexion();
        $sql = "SELECT s.*, u.nombre_usuario AS supervisor_nombre, u.telefono_usuario AS supervisor_telefono, u.email_usuario AS supervisor_email
            FROM sucursal s
            LEFT JOIN usuario u ON s.id_supervisor = u.id_usuario
            ORDER BY s.nombre_sucursal ASC";
        
        $result = pg_query($conn, $sql);
        $sucursales = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $sucursales[] = $row;
            }
        }
        return $sucursales;
    }

    public static function obtenerPorId($id) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM sucursal WHERE id_sucursal = $1";
        $result = pg_query_params($conn, $sql, [$id]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    public static function registrar($nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor) {
        $conn = Database::getConexion();
        
        if (self::existePorNombre($nombre_sucursal)) {
            return false;
        }
        
        $sql = "INSERT INTO sucursal (nombre_sucursal, direccion_sucursal, tipo_sucursal, estado_sucursal, id_supervisor) VALUES ($1, $2, $3, $4, $5)";
        $params = [
            $nombre_sucursal,
            $direccion_sucursal,
            $tipo_sucursal,
            't', 
            $id_supervisor
        ];
        
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function actualizar($id_sucursal, $nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor) {
        $conn = Database::getConexion();
        
        if (self::existePorNombreExcluyendoId($nombre_sucursal, $id_sucursal)) {
            return false;
        }
        
        $sql = "UPDATE sucursal SET nombre_sucursal = $1, direccion_sucursal = $2, tipo_sucursal = $3, id_supervisor = $4 WHERE id_sucursal = $5";
        $params = [
            $nombre_sucursal,
            $direccion_sucursal,
            $tipo_sucursal,
            $id_supervisor,
            $id_sucursal
        ];
        
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function desactivar($id_sucursal) {
        $conn = Database::getConexion();
        $sql = "UPDATE sucursal SET estado_sucursal = false WHERE id_sucursal = $1";
        $result = pg_query_params($conn, $sql, [$id_sucursal]);
        return $result;
    }

    public static function activar($id_sucursal) {
        $conn = Database::getConexion();
        $sql = "UPDATE sucursal SET estado_sucursal = true WHERE id_sucursal = $1";
        $result = pg_query_params($conn, $sql, [$id_sucursal]);
        return $result;
    }

    public static function existePorNombre($nombre_sucursal) {
        $conn = Database::getConexion();
        $sql = "SELECT id_sucursal FROM sucursal WHERE nombre_sucursal = $1";
        $result = pg_query_params($conn, $sql, [$nombre_sucursal]);
        return pg_num_rows($result) > 0;
    }

    public static function existePorNombreExcluyendoId($nombre_sucursal, $id_sucursal) {
        $conn = Database::getConexion();
        $sql = "SELECT id_sucursal FROM sucursal WHERE nombre_sucursal = $1 AND id_sucursal != $2";
        $result = pg_query_params($conn, $sql, [$nombre_sucursal, $id_sucursal]);
        return pg_num_rows($result) > 0;
    }

    public static function obtenerSupervisores() {
        $conn = Database::getConexion();
        $sql = "SELECT id_usuario, nombre_usuario FROM usuario WHERE rol_usuario = 'admin' ORDER BY nombre_usuario ASC";
        $result = pg_query($conn, $sql);
        $supervisores = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $supervisores[] = $row;
            }
        }
        return $supervisores;
    }

    public static function validarCampos($nombre_sucursal, $direccion_sucursal, $tipo_sucursal, $id_supervisor) {
        $errores = [];
        
        if (empty($nombre_sucursal)) {
            $errores[] = 'El nombre de la sucursal es obligatorio';
        }
        
        if (empty($direccion_sucursal)) {
            $errores[] = 'La dirección es obligatoria';
        }
        
        if (empty($tipo_sucursal)) {
            $errores[] = 'El tipo de sucursal es obligatorio';
        }
        
        if (empty($id_supervisor)) {
            $errores[] = 'El supervisor es obligatorio';
        }
        
        return $errores;
    }
} 