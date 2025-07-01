<?php

function getAllCategoriasQuery()
{
    return "SELECT * FROM categoria WHERE estado_categoria = true ORDER BY id_categoria ASC";
}

function getCategoriaByIdQuery()
{
    return "SELECT id_categoria FROM categoria WHERE id_categoria = $1";
}

function getCategoriaByNombreQuery()
{
    return "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1";
}

function getCategoriaByNombreExcludeIdQuery()
{
    return "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1 AND id_categoria != $2";
}

function insertCategoriaQuery()
{
    return "INSERT INTO categoria(nombre_categoria, descripcion_categoria, estado_categoria, creado_en) VALUES ($1, $2, $3, $4)";
}

function updateCategoriaQuery()
{
    return "UPDATE categoria SET nombre_categoria = $1, descripcion_categoria = $2, estado_categoria = $3 WHERE id_categoria = $4";
}

function deleteCategoriaQuery()
{
    return "UPDATE categoria SET estado_categoria = false WHERE id_categoria = $1";
}

function getCategoriasActivasQuery()
{
    return "SELECT * FROM categoria WHERE estado_categoria = true ORDER BY nombre_categoria ASC";
}

function updateEstadoCategoriaQuery()
{
    return "UPDATE categoria SET estado_categoria = $1 WHERE id_categoria = $2";
}
?> 