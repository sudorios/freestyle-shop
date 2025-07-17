<?php

function getObtenerTransferenciasQuery() {
    return "SELECT t.*, p.nombre_producto, u.usuario, s1.nombre_sucursal AS sucursal_origen, s2.nombre_sucursal AS sucursal_destino
        FROM transferencia t
        LEFT JOIN producto p ON t.id_producto = p.id_producto
        LEFT JOIN usuario u ON t.id_usuario = u.id_usuario
        LEFT JOIN sucursal s1 ON t.id_origen = s1.id_sucursal
        LEFT JOIN sucursal s2 ON t.id_destino = s2.id_sucursal
        ORDER BY t.fecha_transferencia DESC";
}

function getObtenerTransferenciaPorIdQuery() {
    return "SELECT * FROM transferencia WHERE id_transferencia = $1";
}

function getInsertarTransferenciaQuery() {
    return "INSERT INTO transferencia (id_sucursal_origen, id_sucursal_destino, id_producto, cantidad, fecha_transferencia, id_usuario)
        VALUES ($1, $2, $3, $4, $5, $6)";
}

function getActualizarTransferenciaQuery() {
    return "UPDATE transferencia SET id_origen = $1, id_destino = $2, id_producto = $3, cantidad = $4, fecha_transferencia = $5, id_usuario = $6 WHERE id_transferencia = $7";
}

function getEliminarTransferenciaQuery() {
    return "DELETE FROM transferencia WHERE id_transferencia = $1";
}

function registrarTransferencia($conn, $origen, $destino, $producto, $cantidad, $fecha, $usuario) {
    $sql_insertar_transferencia = getInsertarTransferenciaQuery();
    $sql_usuario = "SELECT id_usuario FROM usuario WHERE usuario = $1";
    $res_usuario = pg_query_params($conn, $sql_usuario, [$usuario]);
    if (!$res_usuario || pg_num_rows($res_usuario) == 0) {
        return 'Usuario no encontrado';
    }
    $row_usuario = pg_fetch_assoc($res_usuario);
    $id_usuario = $row_usuario['id_usuario'];
    $res = pg_query_params($conn, $sql_insertar_transferencia, [$origen, $destino, $producto, $cantidad, $fecha, $id_usuario]);
    if (!$res) {
        return pg_last_error($conn);
    }
    return true;
}

function getCantidadInventarioSucursalQuery() {
    return "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
}

function getActualizarInventarioSucursalQuery() {
    return "UPDATE inventario_sucursal SET cantidad = $1, fecha_actualizacion = CURRENT_TIMESTAMP WHERE id_producto = $2 AND id_sucursal = $3";
}

function getInsertarInventarioSucursalQuery() {
    return "INSERT INTO inventario_sucursal (id_producto, id_sucursal, cantidad, fecha_actualizacion, estado) VALUES ($1, $2, $3, CURRENT_TIMESTAMP, 'CUADRA')";
}

function getListadoTransferenciasQuery($where_sql = '') {
    return "SELECT t.*, p.nombre_producto, 
        so.nombre_sucursal AS sucursal_origen, 
        sd.nombre_sucursal AS sucursal_destino,
        u.nombre_usuario AS usuario
        FROM transferencia t
        JOIN producto p ON t.id_producto = p.id_producto
        JOIN sucursal so ON t.id_sucursal_origen = so.id_sucursal
        JOIN sucursal sd ON t.id_sucursal_destino = sd.id_sucursal
        JOIN usuario u ON t.id_usuario = u.id_usuario
        $where_sql
        ORDER BY t.fecha_transferencia DESC";
} 

function getSucursalesActivasQuery() {
    return "SELECT id_sucursal, nombre_sucursal FROM sucursal WHERE estado_sucursal = true ORDER BY nombre_sucursal ASC";
}

function getWhereFiltrosTransferencia($conn, $fecha_inicio, $fecha_fin, $origen, $destino) {
    $where = [];
    if ($fecha_inicio !== '') {
        $where[] = "t.fecha_transferencia >= '" . pg_escape_string($conn, $fecha_inicio) . "'";
    }
    if ($fecha_fin !== '') {
        $where[] = "t.fecha_transferencia <= '" . pg_escape_string($conn, $fecha_fin) . " 23:59:59'";
    }
    if ($origen !== '') {
        $where[] = "t.id_sucursal_origen = '" . pg_escape_string($conn, $origen) . "'";
    }
    if ($destino !== '') {
        $where[] = "t.id_sucursal_destino = '" . pg_escape_string($conn, $destino) . "'";
    }
    $where_sql = '';
    if (count($where) > 0) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    return $where_sql;
} 