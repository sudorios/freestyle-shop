<?php
require_once __DIR__ . '/../core/Database.php';

class Kardex {
    public static function obtenerTodos($fecha_inicio = '', $fecha_fin = '', $id_sucursal = '') {
        $conn = Database::getConexion();
        

        
        $where_conditions = [];
        $params = [];
        $param_count = 1;
        
        if (!empty($fecha_inicio)) {
            $where_conditions[] = "k.fecha_movimiento >= $" . $param_count;
            $params[] = $fecha_inicio;
            $param_count++;
        }
        
        if (!empty($fecha_fin)) {
            $where_conditions[] = "k.fecha_movimiento <= $" . $param_count;
            $params[] = $fecha_fin;
            $param_count++;
        }
        
        if (!empty($id_sucursal) && $id_sucursal !== 'todas') {
            $where_conditions[] = "s.id_sucursal = $" . $param_count;
            $params[] = $id_sucursal;
            $param_count++;
        }
        
        $where_sql = '';
        if (!empty($where_conditions)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_conditions);
        }
        
        $sql = "SELECT k.*, (p.nombre_producto || ' (' || COALESCE(p.talla_producto, '') || ')') AS nombre_producto, 
                       u.nombre_usuario AS usuario, s.nombre_sucursal
                FROM kardex k
                JOIN producto p ON k.id_producto = p.id_producto
                JOIN usuario u ON k.id_usuario = u.id_usuario
                LEFT JOIN sucursal s ON k.id_sucursal = s.id_sucursal
                $where_sql
                ORDER BY k.id_kardex DESC";
        

        
        $result = pg_query_params($conn, $sql, $params);
        $kardex = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $kardex[] = $row;
            }
        }
        return $kardex;
    }

    public static function obtenerParaExportar($fecha_inicio = '', $fecha_fin = '') {
        $conn = Database::getConexion();
        
        $where_conditions = [];
        $params = [];
        $param_count = 1;
        
        if (!empty($fecha_inicio)) {
            $where_conditions[] = "k.fecha_movimiento >= $" . $param_count;
            $params[] = $fecha_inicio;
            $param_count++;
        }
        
        if (!empty($fecha_fin)) {
            $where_conditions[] = "k.fecha_movimiento <= $" . $param_count;
            $params[] = $fecha_fin;
            $param_count++;
        }
        
        $where_sql = '';
        if (!empty($where_conditions)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_conditions);
        }
        
        $sql = "SELECT k.id_kardex, k.id_producto, k.cantidad, k.tipo_movimiento, 
                       k.precio_costo, k.fecha_movimiento, k.id_usuario, s.nombre_sucursal
                FROM kardex k
                LEFT JOIN sucursal s ON k.id_sucursal = s.id_sucursal
                $where_sql
                ORDER BY k.id_kardex ASC";
        
        $result = pg_query_params($conn, $sql, $params);
        $kardex = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $kardex[] = $row;
            }
        }
        return $kardex;
    }

    public static function registrar($id_producto, $cantidad, $tipo_movimiento, $precio_costo, $id_usuario, $id_sucursal = null) {
        $conn = Database::getConexion();
        
        $sql = "INSERT INTO kardex (id_producto, cantidad, tipo_movimiento, precio_costo, fecha_movimiento, id_usuario, id_sucursal) 
                VALUES ($1, $2, $3, $4, NOW(), $5, $6)";
        
        $params = [
            $id_producto,
            $cantidad,
            $tipo_movimiento,
            $precio_costo,
            $id_usuario,
            $id_sucursal
        ];
        
        $result = pg_query_params($conn, $sql, $params);
        return $result;
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

    public static function validarFiltros($fecha_inicio, $fecha_fin) {
        $errores = [];
        
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            if (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
                $errores[] = 'La fecha de inicio no puede ser mayor que la fecha de fin';
            }
        }
        
        if (!empty($fecha_inicio) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_inicio)) {
            $errores[] = 'Formato de fecha de inicio inválido';
        }
        
        if (!empty($fecha_fin) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fin)) {
            $errores[] = 'Formato de fecha de fin inválido';
        }
        
        return $errores;
    }
} 