<?php
require_once __DIR__ . '/../core/Database.php';

class Catalogo {
    public static function obtenerTodos() {
        $conn = Database::getConexion();
        $sql = "SELECT 
            cp.id,
            p.nombre_producto,
            ip.url_imagen,
            i.precio_venta,
            cp.estado,
            cp.estado_oferta,
            cp.limite_oferta,
            cp.oferta,
            (i.precio_venta * (1 - (cp.oferta / 100))) AS precio_con_descuento
        FROM 
            catalogo_productos cp
        JOIN 
            producto p ON cp.producto_id = p.id_producto
        JOIN 
            ingreso i ON cp.ingreso_id = i.id
        JOIN 
            imagenes_producto ip ON cp.imagen_id = ip.id
        WHERE
            cp.sucursal_id = 7
        ORDER BY 
            cp.id ASC";
        
        $result = pg_query($conn, $sql);
        $catalogo = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $catalogo[] = $row;
            }
        }
        return $catalogo;
    }

    public static function obtenerProductosDisponibles() {
        $conn = Database::getConexion();
        $sql = "SELECT 
            p.id_producto, 
            p.nombre_producto, 
            p.talla_producto, 
            p.descripcion_producto, 
            MAX(i.precio_venta) AS precio_venta, 
            MAX(i.id) AS ingreso_id, 
            MAX(ip.url_imagen) AS url_imagen, 
            MAX(ip.id) AS imagen_id, 
            MAX(ip.vista_producto) AS vista_producto
        FROM 
            inventario_sucursal isuc
        JOIN 
            producto p ON isuc.id_producto = p.id_producto
        JOIN 
            ingreso i ON p.id_producto = i.id_producto
        JOIN 
            imagenes_producto ip ON p.id_producto = ip.producto_id
        WHERE 
            isuc.id_sucursal = 7
            AND isuc.cantidad > 0
        GROUP BY 
            p.id_producto, 
            p.nombre_producto, 
            p.talla_producto, 
            p.descripcion_producto
        ORDER BY 
            p.nombre_producto";
        
        $result = pg_query($conn, $sql);
        $productos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $productos[] = $row;
            }
        }
        return $productos;
    }

    public static function registrar($producto_id, $sucursal_id, $ingreso_id, $imagen_id, $estado_oferta = false, $limite_oferta = null, $oferta = null) {
        $conn = Database::getConexion();
        
        if (self::existeProducto($producto_id, $sucursal_id)) {
            return false;
        }
        
        $sql = "INSERT INTO catalogo_productos (
            producto_id, 
            sucursal_id, 
            ingreso_id, 
            imagen_id, 
            estado, 
            estado_oferta, 
            limite_oferta, 
            oferta
        ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
        
        $params = [
            $producto_id, 
            $sucursal_id, 
            $ingreso_id, 
            $imagen_id, 
            true,
            $estado_oferta ? 't' : 'f',
            $limite_oferta, 
            $oferta
        ];
        
        $result = pg_query_params($conn, $sql, $params);
        
        return $result;
    }

    public static function activar($id) {
        $conn = Database::getConexion();
        $sql = "UPDATE catalogo_productos SET estado = true WHERE id = $1";
        $result = pg_query_params($conn, $sql, [$id]);
        return $result;
    }

    public static function desactivar($id) {
        $conn = Database::getConexion();
        $sql = "UPDATE catalogo_productos SET estado = false WHERE id = $1";
        $result = pg_query_params($conn, $sql, [$id]);
        return $result;
    }

    public static function existeProducto($producto_id, $sucursal_id) {
        $conn = Database::getConexion();
        $sql = "SELECT 1 FROM catalogo_productos WHERE producto_id = $1 AND sucursal_id = $2";
        $result = pg_query_params($conn, $sql, [$producto_id, $sucursal_id]);
        
        if (!$result) {
            return false;
        }
        
        return pg_num_rows($result) > 0;
    }

    public static function obtenerPorId($id) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM catalogo_productos WHERE id = $1";
        $result = pg_query_params($conn, $sql, [$id]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }
} 