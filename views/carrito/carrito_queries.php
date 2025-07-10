<?php

function query_insertar_item_carrito() {
    return "INSERT INTO carrito_items (carrito_id, producto_id, talla, cantidad, precio_unitario) VALUES ($1, $2, $3, $4, $5)";
}

function query_actualizar_cantidad_item() {
    return "UPDATE carrito_items SET cantidad = $1 WHERE id = $2";
}

function query_eliminar_item_carrito() {
    return "UPDATE carrito_items SET estado = 'eliminado' WHERE id = $1";
}

function query_buscar_item_carrito() {
    return "SELECT id, cantidad FROM carrito_items WHERE carrito_id = $1 AND producto_id = $2 AND talla = $3 AND estado = 'activo'";
}

function query_obtener_item_por_id() {
    return "SELECT id FROM carrito_items WHERE id = $1 AND carrito_id = $2 AND estado = 'activo'";
}

function query_sumar_cantidad_items() {
    return "SELECT SUM(cantidad) AS total FROM carrito_items WHERE carrito_id = $1 AND estado = 'activo'";
}

function query_info_producto_catalogo() {
    return "SELECT cp.producto_id, i.precio_venta FROM catalogo_productos cp JOIN ingreso i ON cp.ingreso_id = i.id WHERE cp.id = $1 LIMIT 1";
}

function query_obtener_items_carrito() {
    return "SELECT ci.id, ci.cantidad, ci.talla, ci.precio_unitario, p.nombre_producto, ip.url_imagen, i.precio_venta, cp.oferta\n                FROM carrito_items ci\n                JOIN producto p ON ci.producto_id = p.id_producto\n                JOIN catalogo_productos cp ON cp.producto_id = p.id_producto\n                JOIN ingreso i ON cp.ingreso_id = i.id\n                LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id\n                WHERE ci.carrito_id = $1 AND ci.estado = 'activo'";
}

function query_obtener_carrito_por_usuario() {
    return "SELECT id FROM carrito WHERE usuario_id = $1";
}

function query_obtener_carrito_por_sesion() {
    return "SELECT id FROM carrito WHERE session_id = $1";
}

function query_insertar_carrito_por_usuario() {
    return "INSERT INTO carrito (usuario_id) VALUES ($1) RETURNING id";
}

function query_insertar_carrito_por_sesion() {
    return "INSERT INTO carrito (session_id) VALUES ($1) RETURNING id";
} 