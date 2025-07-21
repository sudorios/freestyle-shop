<?php
require_once __DIR__ . '/../core/Database.php';

class Transferencia {
    public static function obtenerTodas($filtros = []) {
        $conn = Database::getConexion();
        $where = [];
        if (!empty($filtros['fecha_inicio'])) {
            $where[] = "t.fecha_transferencia >= '" . pg_escape_string($conn, $filtros['fecha_inicio']) . "'";
        }
        if (!empty($filtros['fecha_fin'])) {
            $where[] = "t.fecha_transferencia <= '" . pg_escape_string($conn, $filtros['fecha_fin']) . " 23:59:59'";
        }
        if (!empty($filtros['origen'])) {
            $where[] = "t.id_sucursal_origen = '" . pg_escape_string($conn, $filtros['origen']) . "'";
        }
        if (!empty($filtros['destino'])) {
            $where[] = "t.id_sucursal_destino = '" . pg_escape_string($conn, $filtros['destino']) . "'";
        }
        $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sql = "SELECT t.*, p.nombre_producto, so.nombre_sucursal AS sucursal_origen, sd.nombre_sucursal AS sucursal_destino, u.nombre_usuario AS usuario
                FROM transferencia t
                JOIN producto p ON t.id_producto = p.id_producto
                JOIN sucursal so ON t.id_sucursal_origen = so.id_sucursal
                JOIN sucursal sd ON t.id_sucursal_destino = sd.id_sucursal
                JOIN usuario u ON t.id_usuario = u.id_usuario
                $where_sql
                ORDER BY t.fecha_transferencia DESC";
        $result = pg_query($conn, $sql);
        $transferencias = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $transferencias[] = $row;
            }
        }
        return $transferencias;
    }

    public static function obtenerSucursales() {
        $conn = Database::getConexion();
        $sql = "SELECT id_sucursal, nombre_sucursal, tipo_sucursal FROM sucursal WHERE estado_sucursal = true ORDER BY nombre_sucursal ASC";
        $res = pg_query($conn, $sql);
        $sucursales = [];
        while ($row = pg_fetch_assoc($res)) {
            $sucursales[] = $row;
        }
        return $sucursales;
    }

    public static function obtenerProductosActivos() {
        $conn = Database::getConexion();
        $sql = "SELECT id_producto, nombre_producto, talla_producto FROM producto WHERE estado = true ORDER BY nombre_producto ASC";
        $res = pg_query($conn, $sql);
        $productos = [];
        while ($row = pg_fetch_assoc($res)) {
            $productos[] = $row;
        }
        return $productos;
    }

    public static function validarCampos($origen, $destino, $producto, $cantidad, $fecha) {
        $errores = [];
        if (empty($origen) || !is_numeric($origen)) $errores[] = 'La sucursal de origen es obligatoria y debe ser válida';
        if (empty($destino) || !is_numeric($destino)) $errores[] = 'La sucursal de destino es obligatoria y debe ser válida';
        if ($origen == $destino) $errores[] = 'La sucursal de origen y destino no pueden ser la misma';
        if (empty($producto) || !is_numeric($producto)) $errores[] = 'El producto es obligatorio y debe ser válido';
        if (empty($cantidad) || !is_numeric($cantidad) || $cantidad <= 0) $errores[] = 'La cantidad es obligatoria y debe ser mayor a 0';
        if (empty($fecha)) $errores[] = 'La fecha es obligatoria';
        if (strtotime($fecha) > strtotime(date('Y-m-d'))) $errores[] = 'No se puede registrar una transferencia con fecha futura';
        return $errores;
    }

    public static function registrar($origen, $destino, $producto, $cantidad, $fecha, $id_usuario) {
        $conn = Database::getConexion();
        pg_query($conn, 'BEGIN');
        $sql_insert = "INSERT INTO transferencia (id_sucursal_origen, id_sucursal_destino, id_producto, cantidad, fecha_transferencia, id_usuario) VALUES ($1, $2, $3, $4, $5, $6)";
        $result = pg_query_params($conn, $sql_insert, [$origen, $destino, $producto, $cantidad, $fecha, $id_usuario]);
        if (!$result) {
            pg_query($conn, 'ROLLBACK');
            return pg_last_error($conn);
        }
        $sql_check_origen = "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
        $res_check_origen = pg_query_params($conn, $sql_check_origen, [$producto, $origen]);
        if ($row = pg_fetch_assoc($res_check_origen)) {
            $nueva_cantidad = $row['cantidad'] - $cantidad;
            $sql_update_origen = "UPDATE inventario_sucursal SET cantidad = $1, fecha_actualizacion = CURRENT_TIMESTAMP WHERE id_producto = $2 AND id_sucursal = $3";
            pg_query_params($conn, $sql_update_origen, [$nueva_cantidad, $producto, $origen]);
        }
        $sql_check_destino = "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
        $res_check_destino = pg_query_params($conn, $sql_check_destino, [$producto, $destino]);
        if ($row = pg_fetch_assoc($res_check_destino)) {
            $nueva_cantidad = $row['cantidad'] + $cantidad;
            $sql_update_destino = "UPDATE inventario_sucursal SET cantidad = $1, fecha_actualizacion = CURRENT_TIMESTAMP WHERE id_producto = $2 AND id_sucursal = $3";
            pg_query_params($conn, $sql_update_destino, [$nueva_cantidad, $producto, $destino]);
        } else {
            $sql_insert_destino = "INSERT INTO inventario_sucursal (id_producto, id_sucursal, cantidad, fecha_actualizacion, estado) VALUES ($1, $2, $3, CURRENT_TIMESTAMP, 'CUADRA')";
            pg_query_params($conn, $sql_insert_destino, [$producto, $destino, $cantidad]);
        }
        require_once __DIR__ . '/Kardex.php';
        \Kardex::registrar($producto, $cantidad, 'SALIDA', 0, $id_usuario, $origen);
        \Kardex::registrar($producto, $cantidad, 'INGRESO', 0, $id_usuario, $destino);
        pg_query($conn, 'COMMIT');
        return true;
    }

    public static function obtenerStockProductoSucursal($producto, $sucursal) {
        $conn = Database::getConexion();
        $id_producto = pg_escape_string($conn, $producto);
        $id_sucursal = pg_escape_string($conn, $sucursal);
        $sql_stock = "SELECT COALESCE(cantidad, 0) AS total_stock FROM inventario_sucursal WHERE id_producto = '$id_producto' AND id_sucursal = '$id_sucursal'";
        $res_stock = pg_query($conn, $sql_stock);
        $row_stock = pg_fetch_assoc($res_stock);
        return $row_stock ? $row_stock['total_stock'] : 0;
    }
} 