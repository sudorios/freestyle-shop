<?php

function getOfertasQuery()
{
    return "SELECT 
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
}

function getCategoriasQuery()
{
    return "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = true ORDER BY nombre_categoria ASC";
}

function getProductosPorCategoriaQuery()
{
    return "SELECT cp.id, p.nombre_producto, ip.url_imagen, i.precio_venta
        FROM catalogo_productos cp
        JOIN producto p ON cp.producto_id = p.id_producto
        JOIN ingreso i ON cp.ingreso_id = i.id
        JOIN imagenes_producto ip ON cp.imagen_id = ip.id
        JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria
        WHERE cp.sucursal_id = 7
          AND (cp.estado = true OR cp.estado = 't')
          AND (cp.estado_oferta = false OR cp.estado_oferta = 'f' OR cp.oferta IS NULL OR cp.oferta = 0)
          AND s.id_categoria = $1
        ORDER BY cp.id ASC";
}

function getPedidosQuery($where_sql = '', $order_sql = 'ORDER BY p.fecha DESC', $limit_offset = '')
{
    return "SELECT p.id_pedido, u.nombre_usuario, p.fecha, p.total, p.estado
            FROM pedido p
            LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
            $where_sql
            $order_sql
            $limit_offset";
}

function getPedidoByIdQuery()
{
    return "SELECT p.id_pedido, u.nombre_usuario, p.fecha, p.total, p.estado, p.direccion_envio, p.metodo_pago, p.observaciones
            FROM pedido p
            LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.id_pedido = $1";
}

function getDetallePedidoQuery()
{
    return "SELECT dp.id_detalle, dp.cantidad, dp.precio_unitario, dp.subtotal, p.nombre_producto, p.talla_producto
            FROM detalle_pedido dp
            JOIN producto p ON dp.id_producto = p.id_producto
            WHERE dp.id_pedido = $1";
}

function updatePedidoEstadoQuery()
{
    return "UPDATE pedido SET estado = $1 WHERE id_pedido = $2";
}

function cancelarPedidoQuery()
{
    return "UPDATE pedido SET estado = 'CANCELADO' WHERE id_pedido = $1";
}

function getDetallePedidoParaCancelarQuery()
{
    return "SELECT id_producto, cantidad FROM detalle_pedido WHERE id_pedido = $1";
}

function actualizarStockSucursalQuery()
{
    return "UPDATE inventario_sucursal SET cantidad = cantidad + $1 WHERE id_producto = $2 AND id_sucursal = $3";
}

function countPedidosQuery($where_sql = '')
{
    return "SELECT COUNT(*) FROM pedido p LEFT JOIN usuario u ON p.id_usuario = u.id_usuario $where_sql";
} 

function getCarritoItemsByIdsQuery($placeholders)
{
    return "SELECT ci.id, ci.producto_id, ci.cantidad, ci.precio_unitario, ci.talla, p.nombre_producto, ip.url_imagen
        FROM carrito_items ci
        JOIN producto p ON ci.producto_id = p.id_producto
        LEFT JOIN catalogo_productos cp ON cp.producto_id = p.id_producto
        LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
        WHERE ci.id IN ($placeholders) AND ci.estado = 'activo'";
} 

function getStockPorCatalogoYTallaQuery() {
    return "SELECT 
        cp.id,
        p.nombre_producto,
        p.talla_producto,
        isuc.cantidad,
        ip.url_imagen,
        i.precio_venta,
        (i.precio_venta * (1 - (cp.oferta / 100))) AS precio_con_descuento
    FROM 
        catalogo_productos cp
    JOIN 
        producto p ON cp.producto_id = p.id_producto
    JOIN 
        ingreso i ON cp.ingreso_id = i.id
    JOIN 
        imagenes_producto ip ON cp.imagen_id = ip.id
    JOIN 
        inventario_sucursal isuc ON p.id_producto = isuc.id_producto
    WHERE
        cp.id = $1
        AND p.talla_producto = $2
        AND isuc.cantidad > 0
        AND cp.sucursal_id = 7
        AND isuc.id_sucursal = 7
    ORDER BY 
        p.nombre_producto ASC
    LIMIT 1;";
} 

function obtenerStockPorCatalogoYTalla($conn, $catalogo_id, $talla) {
    $sql = getStockPorCatalogoYTallaQuery();
    $result = pg_query_params($conn, $sql, [$catalogo_id, $talla]);
    return pg_fetch_assoc($result);
} 

function getSucursalesActivasQuery() {
    return "SELECT id_sucursal, nombre_sucursal FROM sucursal WHERE estado_sucursal = true ORDER BY nombre_sucursal ASC";
} 

function getProductoPorIdQuery() {
    return "SELECT 
        cp.id,
        p.id_producto AS producto_id,
        p.nombre_producto,
        p.descripcion_producto,
        p.talla_producto,
        c.nombre_categoria,
        s.nombre_subcategoria,
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
    LEFT JOIN 
        subcategoria s ON p.id_subcategoria = s.id_subcategoria
    LEFT JOIN 
        categoria c ON s.id_categoria = c.id_categoria
    WHERE
        cp.sucursal_id = 7
        AND (cp.estado = true OR cp.estado = 't')
        AND cp.id = $1
    ORDER BY 
        cp.id ASC
    LIMIT 1;";
}

function obtenerProductoPorId($conn, $catalogo_id) {
    $sql = getProductoPorIdQuery();
    $result = pg_query_params($conn, $sql, [$catalogo_id]);
    return pg_fetch_assoc($result);
} 

function getTallasPorCatalogoIdQuery() {
    return "SELECT DISTINCT p.talla_producto  
    FROM catalogo_productos cp
    JOIN producto p ON cp.producto_id = p.id_producto
    JOIN inventario_sucursal isuc ON p.id_producto = isuc.id_producto
    WHERE cp.sucursal_id = 7  
      AND isuc.cantidad > 0  
      AND cp.id = $1
    ORDER BY p.talla_producto ASC;";
}

function obtenerTallasPorCatalogoId($conn, $catalogo_id) {
    $sql = getTallasPorCatalogoIdQuery();
    $res = pg_query_params($conn, $sql, [$catalogo_id]);
    $tallas = [];
    if ($res) {
        while ($row = pg_fetch_assoc($res)) {
            if (!empty($row['talla_producto'])) {
                $tallas[] = $row['talla_producto'];
            }
        }
    }
    return $tallas;
} 

function getImagenesPorProductoIdQuery() {
    return "SELECT * FROM imagenes_producto WHERE producto_id = $1 ORDER BY creado_en DESC";
}

function obtenerImagenesPorProductoId($conn, $producto_id) {
    $sql = getImagenesPorProductoIdQuery();
    $res = pg_query_params($conn, $sql, [$producto_id]);
    $imagenes = [];
    if ($res && pg_num_rows($res) > 0) {
        while ($img = pg_fetch_assoc($res)) {
            $imagenes[] = $img;
        }
    }
    return $imagenes;
} 

function check_rol($roles_permitidos) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $roles_permitidos)) {
        header('Location: /freestyle-shop/index.php?controller=usuario&action=login');
        exit();
    }
} 

function getTotalProductosQuery() {
    return "SELECT COUNT(*) FROM producto";
}
function getTotalPedidosQuery() {
    return "SELECT COUNT(*) FROM pedido";
}
function getTotalIngresosQuery() {
    return "SELECT COUNT(*) FROM ingreso";
}
function getTotalUsuariosQuery() {
    return "SELECT COUNT(*) FROM usuario";
}
function getProductosPorEstadoQuery() {
    return "SELECT estado, COUNT(*) as cantidad FROM inventario_sucursal GROUP BY estado ORDER BY estado";
}
function getPedidosPorMesQuery() {
    return "SELECT TO_CHAR(fecha, 'YYYY-MM') AS mes, COUNT(*) AS cantidad FROM pedido GROUP BY mes ORDER BY mes";
}
function getIngresosPorMesQuery() {
    return "SELECT TO_CHAR(fecha_ingreso, 'YYYY-MM') AS mes, COUNT(*) AS cantidad FROM ingreso GROUP BY mes ORDER BY mes";
}
function getTopProductosVendidosQuery($limit = 5) {
    return "SELECT p.nombre_producto, SUM(dp.cantidad) as total_vendidos
        FROM pedido_detalle dp
        JOIN producto p ON dp.id_producto = p.id_producto
        GROUP BY p.nombre_producto
        ORDER BY total_vendidos DESC
        LIMIT $limit";
} 

// =====================
// === PEDIDOS (VENTA) ===
// =====================

function insertPedidoQuery() {
    // Inserta un pedido con dirección, método de pago y observaciones
    return "INSERT INTO pedido (id_usuario, total, estado, direccion_envio, metodo_pago, observaciones) 
            VALUES ($1, $2, 'PENDIENTE', $3, $4, $5) RETURNING id_pedido";
}

function insertDetallePedidoQuery() {
    // Inserta un detalle de pedido
    return "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) 
            VALUES ($1, $2, $3, $4)";
}

function updateStockVentaQuery() {
    // Actualiza el stock restando cantidad (venta)
    return "UPDATE inventario_sucursal SET cantidad = cantidad - $1 WHERE id_producto = $2 AND id_sucursal = $3";
} 