<?php
require_once __DIR__ . '/../core/Database.php';

class ConteoCiclico {
    public static function obtenerTodos($where_sql = '') {
        $conn = Database::getConexion();
        $sql = "SELECT c.*, u.nombre_usuario FROM conteos_ciclicos c LEFT JOIN usuario u ON c.usuario_id = u.id_usuario $where_sql ORDER BY c.fecha_conteo DESC, c.id_conteo DESC";
        $result = pg_query($conn, $sql);
        $conteos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $conteos[] = $row;
            }
        }
        return $conteos;
    }

    public static function registrar($data) {
        $conn = Database::getConexion();
        $sql = "INSERT INTO conteos_ciclicos (producto_id, sucursal_id, cantidad_real, cantidad_sistema, diferencia, fecha_conteo, usuario_id, comentarios, estado_conteo) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9) RETURNING id_conteo";
        $params = [
            $data['producto_id'],
            $data['sucursal_id'],
            $data['cantidad_real'],
            $data['cantidad_sistema'],
            $data['diferencia'],
            $data['fecha_conteo'],
            $data['usuario_id'],
            $data['comentarios'],
            $data['estado_conteo']
        ];
        $result = pg_query_params($conn, $sql, $params);
        if (!$result) return false;
        // Actualizar estado inventario según diferencia
        $estado_inventario = 'CUADRA';
        if ($data['diferencia'] > 0) {
            $estado_inventario = 'SOBRA';
        } elseif ($data['diferencia'] < 0) {
            $estado_inventario = 'FALTA';
        }
        $sql_update = "UPDATE inventario_sucursal SET estado = $1 WHERE id_producto = $2 AND id_sucursal = $3";
        $ok_update = pg_query_params($conn, $sql_update, [$estado_inventario, $data['producto_id'], $data['sucursal_id']]);
        if (!$ok_update) return false;
        return true;
    }

    public static function actualizar($data) {
        $conn = Database::getConexion();
        $sql = "UPDATE conteos_ciclicos SET cantidad_real = $1, cantidad_sistema = $2, diferencia = $3, fecha_conteo = $4, usuario_id = $5, comentarios = $6, estado_conteo = $7, fecha_ajuste = $8 WHERE id_conteo = $9";
        $params = [
            $data['cantidad_real'],
            $data['cantidad_sistema'],
            $data['diferencia'],
            $data['fecha_conteo'],
            $data['usuario_id'],
            $data['comentarios'],
            $data['estado_conteo'],
            $data['fecha_ajuste'],
            $data['id_conteo']
        ];
        $result = pg_query_params($conn, $sql, $params);
        if (!$result) {
            error_log('Error al actualizar conteo cíclico: ' . pg_last_error($conn));
            return pg_last_error($conn);
        }
        $estado_inventario = 'CUADRA';
        if ($data['diferencia'] > 0) {
            $estado_inventario = 'SOBRA';
        } elseif ($data['diferencia'] < 0) {
            $estado_inventario = 'FALTA';
        }
        $sql_update = "UPDATE inventario_sucursal SET estado = $1 WHERE id_producto = $2 AND id_sucursal = $3";
        $ok_update = pg_query_params($conn, $sql_update, [$estado_inventario, $data['producto_id'], $data['sucursal_id']]);
        if (!$ok_update) return pg_last_error($conn);
        return true;
    }

    public static function obtenerCantidadSistema($producto_id, $sucursal_id) {
        $conn = Database::getConexion();
        $sql = "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
        $result = pg_query_params($conn, $sql, [$producto_id, $sucursal_id]);
        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            return $row['cantidad'];
        }
        return 0;
    }

    public static function obtenerNombreProducto($producto_id) {
        $conn = Database::getConexion();
        $sql = "SELECT nombre_producto FROM producto WHERE id_producto = $1";
        $result = pg_query_params($conn, $sql, [$producto_id]);
        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            return $row['nombre_producto'];
        }
        return '';
    }

    public static function obtenerNombreSucursal($sucursal_id) {
        $conn = Database::getConexion();
        $sql = "SELECT nombre_sucursal FROM sucursal WHERE id_sucursal = $1";
        $result = pg_query_params($conn, $sql, [$sucursal_id]);
        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            return $row['nombre_sucursal'];
        }
        return '';
    }
} 