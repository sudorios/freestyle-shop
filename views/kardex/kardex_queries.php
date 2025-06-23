<?php

$sql_obtener_kardex = "SELECT k.*, p.nombre_producto, u.nombre_usuario AS usuario 
                      FROM kardex k 
                      LEFT JOIN producto p ON k.id_producto = p.id_producto 
                      LEFT JOIN usuario u ON k.id_usuario = u.id_usuario 
                      ORDER BY k.fecha_movimiento DESC";

$sql_obtener_kardex_por_id = "SELECT * FROM kardex WHERE id_kardex = $1";

$sql_insertar_kardex = "INSERT INTO kardex (id_producto, cantidad, tipo_movimiento, precio_costo, fecha_movimiento, id_usuario) 
                        VALUES ($1, $2, $3, $4, $5, $6)";

$sql_actualizar_kardex = "UPDATE kardex SET id_producto = $1, cantidad = $2, tipo_movimiento = $3, precio_costo = $4, fecha_movimiento = $5 WHERE id_kardex = $6";

$sql_eliminar_kardex = "DELETE FROM kardex WHERE id_kardex = $1";

?> 