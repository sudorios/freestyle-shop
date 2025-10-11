<?php
require_once __DIR__ . '/../core/Database.php';

class Categoria {
    public static function obtenerTodas() {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM categoria WHERE habilitado = true ORDER BY categoria_id DESC";
        $result = pg_query($conn, $sql);
        $categorias = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $categorias[] = $row;
            }
        }
        return $categorias;
    }

    public static function obtenerPorId($categoria_id) {
        $conn = Database::getConexion();
        $sql = "SELECT * FROM categoria WHERE categoria_id = $1";
        $result = pg_query_params($conn, $sql, [$categoria_id]);
        if ($result && pg_num_rows($result) > 0) {
            return pg_fetch_assoc($result);
        }
        return null;
    }

    public static function registrar($nombre, $descripcion) {
        $conn = Database::getConexion();
        $sql = "INSERT INTO categoria(nombre, descripcion, habilitado, creado) VALUES ($1, $2, $3, $4)";
        $params = [$nombre, $descripcion, true, date('Y-m-d H:i:s')];
        $result = pg_query_params($conn, $sql, $params);
        return $result;
    }

    public static function actualizar($categoria_id, $nombre, $descripcion, $estado) {
        $conn = Database::getConexion();
        $sql = "UPDATE categoria SET nombre = $1, descripcion = $2, habilitado = $3 WHERE categoria_id = $4";
        $params = [$nombre, $descripcion, $estado, $categoria_id];
        $result = pg_query_params($conn, $sql, $params);
        
        return $result;
    }

    public static function eliminar($categoria_id) {
        $conn = Database::getConexion();
        $sql = "UPDATE categoria SET habilitado = false WHERE categoria_id = $1";
        $result = pg_query_params($conn, $sql, [$categoria_id]);
        return $result;
    }

    public static function existePorNombre($nombre, $excluir_id = null) {
        $conn = Database::getConexion();
        if ($excluir_id) {
            $sql = "SELECT categoria_id FROM categoria WHERE nombre = $1 AND categoria_id != $2";
            $result = pg_query_params($conn, $sql, [$nombre, $excluir_id]);
        } else {
            $sql = "SELECT categoria_id FROM categoria WHERE nombre = $1";
            $result = pg_query_params($conn, $sql, [$nombre]);
        }
        return pg_num_rows($result) > 0;
    }

    public static function obtenerProductosPorCategoria($categoria_id, $id_subcategoria = 0, $orden = 'nombre_asc') {
        $conn = Database::getConexion();
        switch ($orden) {
            case 'nombre_desc':
                $order_by = 'p.nombre_producto DESC';
                break;
            case 'precio_asc':
                $order_by = 'i.precio_venta ASC';
                break;
            case 'precio_desc':
                $order_by = 'i.precio_venta DESC';
                break;
            default:
                $order_by = 'p.nombre_producto ASC';
        }
        $sql = "SELECT 
                cp.id AS id_catalogo,
                p.nombre_producto,
                p.descripcion_producto,
                ip.url_imagen,
                i.precio_venta,
                cp.oferta,
                c.nombre
            FROM 
                catalogo_productos cp
            JOIN producto p ON cp.producto_id = p.id_producto
            JOIN ingreso i ON cp.ingreso_id = i.id
            LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
            JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria
            JOIN categoria c ON s.categoria_id = c.categoria_id
            WHERE 
                cp.sucursal_id = 7
                AND (cp.estado = true OR cp.estado = 't')
                AND c.categoria_id = $1";
        $params = [$categoria_id];
        if ($id_subcategoria > 0) {
            $sql .= " AND s.id_subcategoria = $2";
            $params[] = $id_subcategoria;
        }
        $sql .= " ORDER BY $order_by";
        $result = pg_query_params($conn, $sql, $params);
        $productos = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $productos[] = $row;
            }
        }
        return $productos;
    }
} 