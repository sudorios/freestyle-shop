<?php


$sql_obtener_productos = "SELECT p.*, s.nombre_subcategoria FROM producto p LEFT JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria ORDER BY p.id_producto ASC";

$sql_obtener_producto_por_id = "SELECT * FROM producto WHERE id_producto = $1";

$sql_insertar_producto = "INSERT INTO producto (ref_producto, nombre_producto, descripcion_producto, id_subcategoria, talla_producto) VALUES ($1, $2, $3, $4, $5)";

$sql_actualizar_producto = "UPDATE producto SET ref_producto = $1, nombre_producto = $2, descripcion_producto = $3, id_subcategoria = $4, talla_producto = $5, actualizado_en = CURRENT_TIMESTAMP WHERE id_producto = $6";

$sql_eliminar_producto = "DELETE FROM producto WHERE id_producto = $1"; 