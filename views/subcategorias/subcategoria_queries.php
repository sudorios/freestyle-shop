<?php

$sql_obtener_subcategorias = "SELECT s.*, c.nombre_categoria FROM subcategoria s JOIN categoria c ON s.id_categoria = c.id_categoria ORDER BY s.id_subcategoria DESC";
$sql_obtener_subcategoria_por_id = "SELECT * FROM subcategoria WHERE id_subcategoria = $1";
$sql_insertar_subcategoria = "INSERT INTO subcategoria (nombre_subcategoria, descripcion_subcategoria, id_categoria) VALUES ($1, $2, $3)";
$sql_actualizar_subcategoria = "UPDATE subcategoria SET nombre_subcategoria = $1, descripcion_subcategoria = $2, id_categoria = $3, actualizado_en = CURRENT_TIMESTAMP WHERE id_subcategoria = $4";
$sql_eliminar_subcategoria = "DELETE FROM subcategoria WHERE id_subcategoria = $1"; 