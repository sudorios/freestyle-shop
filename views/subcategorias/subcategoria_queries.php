<?php

function getAllSubcategoriasQuery()
{
    return "SELECT s.*, c.nombre_categoria FROM subcategoria s JOIN categoria c ON s.id_categoria = c.id_categoria ORDER BY s.id_subcategoria DESC";
}

function getSubcategoriaByIdQuery()
{
    return "SELECT * FROM subcategoria WHERE id_subcategoria = $1";
}

function getSubcategoriaByNombreQuery()
{
    return "SELECT id_subcategoria FROM subcategoria WHERE nombre_subcategoria = $1";
}

function getSubcategoriaByNombreExcludeIdQuery()
{
    return "SELECT id_subcategoria FROM subcategoria WHERE nombre_subcategoria = $1 AND id_subcategoria != $2";
}

function insertSubcategoriaQuery()
{
    return "INSERT INTO subcategoria (nombre_subcategoria, descripcion_subcategoria, id_categoria) VALUES ($1, $2, $3)";
}

function updateSubcategoriaQuery()
{
    return "UPDATE subcategoria SET nombre_subcategoria = $1, descripcion_subcategoria = $2, id_categoria = $3, actualizado_en = CURRENT_TIMESTAMP WHERE id_subcategoria = $4";
}

function deleteSubcategoriaQuery()
{
    return "DELETE FROM subcategoria WHERE id_subcategoria = $1";
}

function getSubcategoriasByCategoriaQuery()
{
    return "SELECT * FROM subcategoria WHERE id_categoria = $1 ORDER BY nombre_subcategoria ASC";
}

function getSubcategoriasActivasQuery()
{
    return "SELECT s.*, c.nombre_categoria FROM subcategoria s JOIN categoria c ON s.id_categoria = c.id_categoria WHERE s.estado_subcategoria = true ORDER BY s.nombre_subcategoria ASC";
}
?> 