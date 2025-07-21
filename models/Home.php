<?php
require_once __DIR__ . '/../core/Database.php';
class Home {
    public static function obtenerOfertas() {
        $conn = Database::getConexion();
        $sql = "SELECT 
            cp.id,
            p.nombre_producto,
            ip.url_imagen,
            i.precio_venta,
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
            AND (cp.estado = true OR cp.estado = 't')
            AND (cp.estado_oferta = true OR cp.estado_oferta = 't')
        ORDER BY 
            cp.id ASC";
        $result = pg_query($conn, $sql);
        $ofertas = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $ofertas[] = $row;
            }
        }
        return $ofertas;
    }
    public static function obtenerProductosDestacados() {
        $conn = Database::getConexion();
        $sql = "SELECT cp.id, p.nombre_producto, ip.url_imagen, i.precio_venta
            FROM catalogo_productos cp
            JOIN producto p ON cp.producto_id = p.id_producto
            JOIN ingreso i ON cp.ingreso_id = i.id
            JOIN imagenes_producto ip ON cp.imagen_id = ip.id
            WHERE cp.sucursal_id = 7
              AND (cp.estado = true OR cp.estado = 't')
              AND (cp.estado_oferta = false OR cp.estado_oferta = 'f' OR cp.oferta IS NULL OR cp.oferta = 0)
            ORDER BY cp.id ASC
            LIMIT 8";
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