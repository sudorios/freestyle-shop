<?php
require_once __DIR__ . '/../core/Database.php';

class Ingreso {
    public static function obtenerTodos($where_sql = '') {
        $conn = Database::getConexion();
        $sql = "SELECT i.*, p.nombre_producto, p.talla_producto, s.nombre_sucursal, u.nombre_usuario AS usuario
                FROM ingreso i
                JOIN producto p ON i.id_producto = p.id_producto
                LEFT JOIN sucursal s ON i.id_sucursal = s.id_sucursal
                JOIN usuario u ON i.id_usuario = u.id_usuario
                $where_sql
                ORDER BY i.fecha_ingreso DESC";
        $result = pg_query($conn, $sql);
        $ingresos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $ingresos[] = $row;
            }
        }
        return $ingresos;
    }

    public static function registrar($data) {
        $conn = Database::getConexion();
        $sql = "INSERT INTO ingreso (ref, id_producto, precio_costo, precio_costo_igv, precio_venta, utilidad_esperada, utilidad_neta, cantidad, fecha_ingreso, id_usuario, id_sucursal)
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11) RETURNING id";
        $params = [
            $data['ref'],
            $data['id_producto'],
            $data['precio_costo'],
            $data['precio_costo_igv_paquete'],
            $data['precio_venta'],
            $data['utilidad_esperada_total'],
            $data['utilidad_neta_total'],
            $data['cantidad'],
            $data['fecha_ingreso'],
            $data['id_usuario'],
            $data['id_sucursal']
        ];
        $result = pg_query_params($conn, $sql, $params);
        if (!$result) return false;

        $sql_check = "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
        $res_check = pg_query_params($conn, $sql_check, [$data['id_producto'], $data['id_sucursal']]);
        if ($res_check && ($row = pg_fetch_assoc($res_check))) {
            $nueva_cantidad = $row['cantidad'] + $data['cantidad'];
            $sql_update = "UPDATE inventario_sucursal SET cantidad = $1, fecha_actualizacion = CURRENT_TIMESTAMP WHERE id_producto = $2 AND id_sucursal = $3";
            $ok_update = pg_query_params($conn, $sql_update, [$nueva_cantidad, $data['id_producto'], $data['id_sucursal']]);
            if (!$ok_update) return false;
        } else {
            $sql_insert = "INSERT INTO inventario_sucursal (id_producto, id_sucursal, cantidad, fecha_actualizacion, estado) VALUES ($1, $2, $3, CURRENT_TIMESTAMP, 'CUADRA')";
            $ok_insert = pg_query_params($conn, $sql_insert, [$data['id_producto'], $data['id_sucursal'], $data['cantidad']]);
            if (!$ok_insert) return false;
        }

        require_once __DIR__ . '/Kardex.php';
        $precio_costo_unidad = $data['cantidad'] > 0 ? $data['precio_costo'] / $data['cantidad'] : 0;
        $ok_kardex = Kardex::registrar(
            $data['id_producto'],
            $data['cantidad'],
            'INGRESO',
            $precio_costo_unidad,
            $data['id_usuario'],
            $data['id_sucursal']
        );
        if (!$ok_kardex) return false;
        return true;
    }

    public static function actualizar($data) {
        $conn = Database::getConexion();
        $sql = "UPDATE ingreso SET fecha_ingreso = $1, cantidad = $2, precio_costo_igv = $3, precio_venta = $4 WHERE id = $5";
        $params = [
            $data['fecha_ingreso'],
            $data['cantidad'],
            $data['precio_costo_igv'],
            $data['precio_venta'],
            $data['id_ingreso']
        ];
        $result = pg_query_params($conn, $sql, $params);
        if (!$result) {
            error_log('Error al actualizar ingreso: ' . pg_last_error($conn));
            return pg_last_error($conn);
        }
        return true;
    }

    public static function validarCampos($data) {
        $errores = [];
        if (empty($data['ref'])) $errores[] = 'La referencia es obligatoria';
        if (empty($data['id_producto']) || !is_numeric($data['id_producto'])) $errores[] = 'El producto es obligatorio y debe ser válido';
        if (empty($data['precio_costo']) || !is_numeric($data['precio_costo']) || $data['precio_costo'] <= 0) $errores[] = 'El precio de costo es obligatorio y debe ser mayor a 0';
        if (empty($data['precio_venta']) || !is_numeric($data['precio_venta']) || $data['precio_venta'] <= 0) $errores[] = 'El precio de venta es obligatorio y debe ser mayor a 0';
        if (empty($data['cantidad']) || !is_numeric($data['cantidad']) || $data['cantidad'] <= 0) $errores[] = 'La cantidad es obligatoria y debe ser mayor a 0';
        if (empty($data['fecha_ingreso'])) $errores[] = 'La fecha de ingreso es obligatoria';
        return $errores;
    }

    public static function obtenerPorId($id_ingreso) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM ingreso WHERE id_ingreso = $1";
        $result = pg_query_params($conn, $sql, [$id_ingreso]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
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

    public static function obtenerProductosActivos() {
        $conn = Database::getConexion();
        $sql = "SELECT id_producto, nombre_producto, talla_producto FROM producto WHERE estado = true ORDER BY nombre_producto ASC";
        $result = pg_query($conn, $sql);
        $productos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $productos[] = $row;
            }
        }
        return $productos;
    }
} 