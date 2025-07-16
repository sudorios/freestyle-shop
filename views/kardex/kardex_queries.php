<?php

function getKardexListadoQuery($where_sql = '') {
    return "SELECT k.*, (p.nombre_producto || ' (' || COALESCE(p.talla_producto, '') || ')') AS nombre_producto, u.nombre_usuario AS usuario, s.nombre_sucursal
            FROM kardex k
            JOIN producto p ON k.id_producto = p.id_producto
            JOIN usuario u ON k.id_usuario = u.id_usuario
            LEFT JOIN sucursal s ON k.id_sucursal = s.id_sucursal
            $where_sql
            ORDER BY k.id_kardex DESC";
}

function getSucursalesActivasQuery() {
    return "SELECT id_sucursal, nombre_sucursal FROM sucursal WHERE estado_sucursal = true ORDER BY nombre_sucursal ASC";
}

function getKardexExportPdfQuery($where_sql = '') {
    return "SELECT k.id_kardex, k.id_producto, k.cantidad, k.tipo_movimiento, k.precio_costo, k.fecha_movimiento, k.id_usuario, s.nombre_sucursal
            FROM kardex k
            LEFT JOIN sucursal s ON k.id_sucursal = s.id_sucursal
            $where_sql
            ORDER BY k.id_kardex ASC";
}

function getKardexExportCsvQuery() {
    return "SELECT k.id_kardex, k.id_producto, k.cantidad, k.tipo_movimiento, k.precio_costo, k.fecha_movimiento, k.id_usuario, s.nombre_sucursal
            FROM kardex k
            LEFT JOIN sucursal s ON k.id_sucursal = s.id_sucursal
            ORDER BY k.id_kardex ASC";
}

?> 