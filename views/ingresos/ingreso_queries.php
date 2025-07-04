<?php

function getObtenerIngresosQuery() {
    return "SELECT i.*, p.nombre_producto, u.usuario FROM ingreso i 
            LEFT JOIN producto p ON i.id_producto = p.id_producto 
            LEFT JOIN usuario u ON i.id_usuario = u.id_usuario 
            ORDER BY i.fecha_ingreso DESC";
}

function getObtenerIngresoPorIdQuery() {
    return "SELECT * FROM ingreso WHERE id_ingreso = $1";
}

function getInsertarIngresoQuery() {
    return "INSERT INTO ingreso (ref, id_producto, precio_costo, precio_costo_igv, precio_venta, utilidad_esperada, utilidad_neta, cantidad, fecha_ingreso, id_usuario, id_sucursal) 
            VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";
}

function getActualizarIngresoQuery() {
    return "UPDATE ingreso SET referencia = $1, id_producto = $2, precio_costo = $3, precio_costo_igv = $4, precio_venta = $5, utilidad_esperada = $6, utilidad_neta = $7, cantidad = $8, fecha_ingreso = $9, actualizado_en = CURRENT_TIMESTAMP WHERE id_ingreso = $10";
}

function getEliminarIngresoQuery() {
    return "DELETE FROM ingreso WHERE id_ingreso = $1";
}

?> 