<?php

function getAllSucursalesQuery() {
    return "SELECT s.*, u.nombre_usuario AS supervisor_nombre, u.telefono_usuario AS supervisor_telefono, u.email_usuario AS supervisor_email
        FROM sucursal s
        LEFT JOIN usuario u ON s.id_supervisor = u.id_usuario
        ORDER BY s.nombre_sucursal ASC";
}

function getSucursalByIdQuery() {
    return "SELECT * FROM sucursal WHERE id_sucursal = $1";
}

function insertSucursalQuery() {
    return "INSERT INTO sucursal (nombre_sucursal, direccion_sucursal, tipo_sucursal, estado_sucursal, id_supervisor) VALUES ($1, $2, $3, $4, $5)";
}

function updateSucursalQuery() {
    return "UPDATE sucursal SET nombre_sucursal = $1, direccion_sucursal = $2, tipo_sucursal = $3, estado_sucursal = $4, id_supervisor = $5 WHERE id_sucursal = $6";
}

function deleteSucursalQuery() {
    return "UPDATE sucursal SET estado_sucursal = false WHERE id_sucursal = $1";
}

function updateEstadoSucursalQuery() {
    return "UPDATE sucursal SET estado_sucursal = $1 WHERE id_sucursal = $2";
}

function getSucursalByNombreQuery() {
    return "SELECT id_sucursal FROM sucursal WHERE nombre_sucursal = $1";
}

function getSucursalByNombreExcludeIdQuery() {
    return "SELECT id_sucursal FROM sucursal WHERE nombre_sucursal = $1 AND id_sucursal != $2";
}

?> 