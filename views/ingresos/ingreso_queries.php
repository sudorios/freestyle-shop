<?php

$sql_obtener_ingresos = "SELECT i.*, p.nombre_producto, u.usuario FROM ingreso i 
                        LEFT JOIN producto p ON i.id_producto = p.id_producto 
                        LEFT JOIN usuario u ON i.id_usuario = u.id_usuario 
                        ORDER BY i.fecha_ingreso DESC";

$sql_obtener_ingreso_por_id = "SELECT * FROM ingreso WHERE id_ingreso = $1";

$sql_insertar_ingreso = "INSERT INTO ingreso (ref, id_producto, precio_costo, precio_costo_igv, precio_venta, utilidad_esperada, utilidad_neta, cantidad, fecha_ingreso, id_usuario) 
                        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";

$sql_actualizar_ingreso = "UPDATE ingreso SET referencia = $1, id_producto = $2, precio_costo = $3, precio_costo_igv = $4, precio_venta = $5, utilidad_esperada = $6, utilidad_neta = $7, cantidad = $8, fecha_ingreso = $9, actualizado_en = CURRENT_TIMESTAMP WHERE id_ingreso = $10";

$sql_eliminar_ingreso = "DELETE FROM ingreso WHERE id_ingreso = $1";

?> 