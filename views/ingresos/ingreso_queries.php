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

function updateIngresos() {
    return "UPDATE ingreso SET fecha_ingreso = $1, cantidad = $2, precio_costo_igv = $3, precio_venta = $4 WHERE id = $5";
}

function setEstadoCatalogoProductoQuery() {
    return "UPDATE catalogo_productos SET estado = $1 WHERE id = $2";
}

function getCantidadInventarioSucursalQuery() {
    return "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
}

function updateInventarioSucursalQuery() {
    return "UPDATE inventario_sucursal SET cantidad = $1, fecha_actualizacion = CURRENT_TIMESTAMP WHERE id_producto = $2 AND id_sucursal = $3";
}

function insertInventarioSucursalQuery() {
    return "INSERT INTO inventario_sucursal (id_producto, id_sucursal, cantidad, fecha_actualizacion, estado) VALUES ($1, $2, $3, CURRENT_TIMESTAMP, 'CUADRA')";
}

function getListadoIngresosQuery($where_sql = '') {
    return "SELECT i.*, p.nombre_producto, p.talla_producto, s.nombre_sucursal, u.nombre_usuario AS usuario
            FROM ingreso i
            JOIN producto p ON i.id_producto = p.id_producto
            LEFT JOIN sucursal s ON i.id_sucursal = s.id_sucursal
            JOIN usuario u ON i.id_usuario = u.id_usuario
            $where_sql
            ORDER BY i.fecha_ingreso DESC";
}

?> 