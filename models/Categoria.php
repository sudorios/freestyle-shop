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

    public static function obtenerProductosPorCategoria($id_categoria, $id_subcategoria = 0, $orden = 'nombre_asc') {
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
                c.nombre_categoria
            FROM 
                catalogo_productos cp
            JOIN producto p ON cp.producto_id = p.id_producto
            JOIN ingreso i ON cp.ingreso_id = i.id
            LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
            JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria
            JOIN categoria c ON s.id_categoria = c.id_categoria
            WHERE 
                cp.sucursal_id = 7
                AND (cp.estado = true OR cp.estado = 't')
                AND c.id_categoria = $1";
        $params = [$id_categoria];
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