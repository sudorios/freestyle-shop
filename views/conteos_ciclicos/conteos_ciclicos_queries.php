<?php

function getAllConteosCiclicosQuery()
{
    return "SELECT * FROM conteos_ciclicos ORDER BY fecha_conteo DESC, id_conteo DESC";
}

function getConteoCiclicoByIdQuery()
{
    return "SELECT * FROM conteos_ciclicos WHERE id_conteo = $1";
}

function getConteosCiclicosByProductoSucursalQuery()
{
    return "SELECT c.*, u.nombre_usuario FROM conteos_ciclicos c LEFT JOIN usuario u ON c.usuario_id = u.id_usuario WHERE c.producto_id = $1 AND c.sucursal_id = $2 ORDER BY c.fecha_conteo DESC, c.id_conteo DESC";
}

function insertConteoCiclicoQuery()
{
    return "INSERT INTO conteos_ciclicos (producto_id, sucursal_id, cantidad_real, cantidad_sistema, diferencia, fecha_conteo, usuario_id, comentarios, estado_conteo) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
}

function updateConteoCiclicoQuery()
{
    return "UPDATE conteos_ciclicos SET cantidad_real = $1, cantidad_sistema = $2, diferencia = $3, fecha_conteo = $4, usuario_id = $5, comentarios = $6, estado_conteo = $7, fecha_ajuste = $8 WHERE id_conteo = $9";
}

function softDeleteConteoCiclicoQuery()
{
    return "UPDATE conteos_ciclicos SET estado_conteo = 'eliminado' WHERE id_conteo = $1";
}

function getNombreProductoByIdQuery()
{
    return "SELECT nombre_producto FROM producto WHERE id_producto = $1";
}

function getNombreSucursalByIdQuery()
{
    return "SELECT nombre_sucursal FROM sucursal WHERE id_sucursal = $1";
}

function getCantidadInventarioByProductoSucursalQuery()
{
    return "SELECT cantidad FROM inventario_sucursal WHERE id_producto = $1 AND id_sucursal = $2";
}

function getConteosCiclicosFiltradosQuery($conDesde, $conHasta, $usuario, $estado) {
    $sql = "SELECT c.*, u.nombre_usuario FROM conteos_ciclicos c LEFT JOIN usuario u ON c.usuario_id = u.id_usuario WHERE c.producto_id = $1 AND c.sucursal_id = $2";
    $paramIdx = 3;
    if ($conDesde && $conHasta) {
        $sql .= " AND c.fecha_conteo BETWEEN $" . $paramIdx . " AND $" . ($paramIdx+1);
        $paramIdx += 2;
    } elseif ($conDesde) {
        $sql .= " AND c.fecha_conteo >= $" . $paramIdx; $paramIdx++;
    } elseif ($conHasta) {
        $sql .= " AND c.fecha_conteo <= $" . $paramIdx; $paramIdx++;
    }
    if ($usuario) {
        $sql .= " AND (u.nombre_usuario ILIKE $" . $paramIdx . " OR CAST(c.usuario_id AS TEXT) ILIKE $" . $paramIdx . ")"; $paramIdx++;
    }
    if ($estado) {
        $sql .= " AND c.estado_conteo = $" . $paramIdx; $paramIdx++;
    }
    $sql .= " ORDER BY c.fecha_conteo DESC, c.id_conteo DESC";
    return $sql;
}

function updateEstadoInventarioQuery() {
    return "UPDATE inventario_sucursal SET estado = $1 WHERE id_producto = $2 AND id_sucursal = $3";
}

function getUltimoConteoCiclicoByProductoSucursalQuery() {
    return "SELECT id_conteo, fecha_conteo FROM conteos_ciclicos WHERE producto_id = $1 AND sucursal_id = $2 ORDER BY fecha_conteo DESC, id_conteo DESC LIMIT 1";
} 