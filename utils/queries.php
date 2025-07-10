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

// Consultas de pedidos
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