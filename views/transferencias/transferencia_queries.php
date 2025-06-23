<?php

// Query para obtener todas las transferencias
$sql_obtener_transferencias = "SELECT t.*, p.nombre_producto, u.usuario, s1.nombre_sucursal AS sucursal_origen, s2.nombre_sucursal AS sucursal_destino
    FROM transferencia t
    LEFT JOIN producto p ON t.id_producto = p.id_producto
    LEFT JOIN usuario u ON t.id_usuario = u.id_usuario
    LEFT JOIN sucursal s1 ON t.id_origen = s1.id_sucursal
    LEFT JOIN sucursal s2 ON t.id_destino = s2.id_sucursal
    ORDER BY t.fecha_transferencia DESC";

// Query para obtener transferencia por id
$sql_obtener_transferencia_por_id = "SELECT * FROM transferencia WHERE id_transferencia = $1";

// Query para insertar transferencia
$sql_insertar_transferencia = "INSERT INTO transferencia (id_sucursal_origen, id_sucursal_destino, id_producto, cantidad, fecha_transferencia, id_usuario)
    VALUES ($1, $2, $3, $4, $5, $6)";

// Query para actualizar transferencia
$sql_actualizar_transferencia = "UPDATE transferencia SET id_origen = $1, id_destino = $2, id_producto = $3, cantidad = $4, fecha_transferencia = $5, id_usuario = $6 WHERE id_transferencia = $7";

// Query para eliminar transferencia
$sql_eliminar_transferencia = "DELETE FROM transferencia WHERE id_transferencia = $1";

function registrarTransferencia($conn, $origen, $destino, $producto, $cantidad, $fecha, $usuario) {
    global $sql_insertar_transferencia;
    // Obtener id_usuario
    $sql_usuario = "SELECT id_usuario FROM usuario WHERE usuario = $1";
    $res_usuario = pg_query_params($conn, $sql_usuario, [$usuario]);
    if (!$res_usuario || pg_num_rows($res_usuario) == 0) {
        return 'Usuario no encontrado';
    }
    $row_usuario = pg_fetch_assoc($res_usuario);
    $id_usuario = $row_usuario['id_usuario'];
    // Insertar transferencia
    $res = pg_query_params($conn, $sql_insertar_transferencia, [$origen, $destino, $producto, $cantidad, $fecha, $id_usuario]);
    if (!$res) {
        return pg_last_error($conn);
    }
    // TODO: Actualizar inventario de origen y destino
    return true;
} 