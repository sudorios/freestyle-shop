<?php
// Centraliza las consultas relacionadas con pedidos y detalle_pedido

function query_insertar_pedido() {
    return "INSERT INTO pedido (id_usuario, total, estado, direccion_envio, metodo_pago, observaciones) 
            VALUES ($1, $2, 'PENDIENTE', $3, $4, $5) RETURNING id_pedido";
}

function query_insertar_detalle_pedido() {
    return "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) 
            VALUES ($1, $2, $3, $4)";
}

function query_actualizar_stock_producto() {
    return "UPDATE inventario_sucursal SET cantidad = cantidad - $1 WHERE id_producto = $2 AND id_sucursal = $3";
} 